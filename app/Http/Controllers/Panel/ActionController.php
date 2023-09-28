<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Cluster;
use App\Models\Step;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

class ActionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    function __construct()
    {
        $this->middleware('permission:user-evaluation');
        $this->middleware('permission:user-evaluation', ['only' => ['evaluation', 'submit_action']]);
    }

    public function evaluation(Request $request){
        $admin = Auth::user();
        $request->validate([
            'cluster_id' => ['required', Rule::in(Cluster::pluck('id'))],
            'user_id' => ['required', Rule::in($admin->users()->pluck('id'))],
            'month' => ['required', 'numeric', 'min:1', 'max:12']
        ]);

        $user = User::find($request->get('user_id'));

        if($user->admin_id != $admin->id){
            return $this->accessDenied("مراجعه کننده مذکور، به شما ارجاع داده نشده است و شما دسترسی لازم برای گرفتن عملکرد ندارید.");
        }

        $cluster_id = $request->get('cluster_id');

        //last score-------------------------------------------------------------------------------------
        $last_actions = $user->actions()
            ->whereNull('admin_id')
            ->whereHas('step', function($q) use ($cluster_id) {
                $q->where('cluster_id', $cluster_id);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('step_id');

        $sum = 0;
        $count = 0;
        foreach ($last_actions as $last_action){
            $sum += $last_action->count * $last_action->result;
            $count += $last_action->count;
        }
        if($count > 0)
            $last_action_score = $sum / $count;
        else
            $last_action_score = 0;

        //get scores with dates--------------------------------------------------------------------------------------
        $month = $request->get('month');
        $last_day = $month < 7 ? 31 : 30;

        $results = [];
        $visit_results = [];
        $visit_dates = [];

        for($i = 1; $i <= $last_day; $i++){
            $start_date = new Jalalian(Jalalian::now()->getYear(), $month, $i, 0, 0, 0);
            $end_date = new Jalalian(Jalalian::now()->getYear(), $month, $i, 23, 59, 59);

            $actions = $user->actions()
                            ->whereNull('admin_id')
                            ->whereHas('step', function($q) use ($cluster_id) {
                                $q->where('cluster_id', $cluster_id);
                            })
                            ->whereBetween('created_at', [$start_date->toCarbon(), $end_date->toCarbon()])
                            ->groupBy('step_id')
                            ->selectRaw('sum(count * result)/sum(count) as avg')
                            ->get()
                            ->sum('avg');

            $results[] = $actions;
        }

        $visit_actions = $user->actions()
            ->whereNotNull('admin_id')
            ->whereHas('step', function($q) use ($cluster_id) {
                $q->where('cluster_id', $cluster_id);
            })
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('step_id as step_id'))
            ->groupBy('date', 'step_id')
            ->selectRaw('sum(count * result)/sum(count) as avg')
            ->get();

        $last_date = null;
        foreach ($visit_actions as $visit_action){
            if($visit_action->date == $last_date){
                $last_visit_score_in_a_day = array_pop($visit_results);
                $last_visit_score_in_a_day += $visit_action->avg;
                array_push($visit_results, $last_visit_score_in_a_day);
            }else{
                $visit_dates[] = CalendarUtils::strftime('m/d', strtotime($visit_action->date));
                $last_date = $visit_action->date;
                array_push($visit_results, $visit_action->avg);;
            }
        }

        $data = [
            'last_action_score' => round($last_action_score, 2),
            'last_visit_action_score' => count($visit_results) > 0 ? round(end($visit_results), 2) : 0,
            'results' => $results,
            'visit_dates' => $visit_dates,
            'visit_results' => $visit_results
        ];

        return $this->customSuccess($data, "ارزیابی");
    }

    public function submit_action(Request $request){
        $request->validate([
            'user_id' => ['required', Rule::in(User::pluck('id'))],
            'step_id' => ['required', Rule::in(Step::pluck('id'))],
            'count' => ['required', 'numeric', 'min:1', 'max:20'],
            'result' => ['required', 'numeric', 'min:0', 'max:6'],
        ]);

        $admin_id = Auth::id();
        $data = array_merge($request->only('step_id', 'count', 'result'), ['admin_id' => $admin_id]);

        $user = User::find($request->get('user_id'));

        $user->actions()->create($data);

        $step = Step::find($request->get('step_id'));

        return $this->customSuccess(1, "عملکرد مرحله " . $step->number . " با موفقیت ثبت شد.");
    }
}
