<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Step;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

class ActionController extends Controller
{
    public function store_action(Request $request){
        $request->validate([
            'step_id' => ['required', Rule::in(Step::pluck('id'))],
            'count' => ['required', 'numeric', 'min:1', 'max:20'],
            'result' => ['required', 'numeric', 'min:0', 'max:2'],
        ]);

        $user = Auth::user();

        $user->actions()->create($request->only('step_id', 'count', 'result'));

        return $this->customSuccess(1, "عملیات با موفقیت ذخیره شد.");
    }

    public function evaluation(Request $request){
        $user = Auth::user();
        $request->validate([
            'content_id' => ['required', Rule::in($user->contents()->pluck('id'))],
            'month' => ['required', 'numeric', 'min:1', 'max:12']
        ]);

        //finding steps
        $content_id = $request->get('content_id');
        $content = $user->contents()->find($content_id);
        $cluster_id = $content->pivot->cluster_id;

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
            'results' => $results,
            'visit_dates' => $visit_dates,
            'visit_results' => $visit_results
        ];

        return $this->customSuccess($data, "ارزیابی");
    }
}
