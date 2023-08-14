<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Cluster;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
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
        $this->middleware('permission:user-evaluation', ['only' => ['evaluation']]);
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

        $results = [];

        //finding steps
        $cluster_id = $request->get('cluster_id');

        $month = $request->get('month');
        $last_day = $month < 7 ? 31 : 30;

        for($i = 1; $i <= $last_day; $i++){
            $start_date = new Jalalian(Jalalian::now()->getYear(), $month, $i, 0, 0, 0);
            $end_date = new Jalalian(Jalalian::now()->getYear(), $month, $i, 23, 59, 59);

            $actions = $user->actions()
                            ->whereHas('step', function($q) use ($cluster_id) {
                                $q->whereHas('cluster', function($p) use ($cluster_id) {
                                    $p->where('id', $cluster_id);
                                });
                            })
                            ->whereBetween('created_at', [$start_date->toCarbon(), $end_date->toCarbon()])
                            ->groupBy('step_id')
                            ->selectRaw('sum(count * result)/sum(count) as avg')
                            ->get()
                            ->sum('avg');

            $results[] = $actions;
        }

        return $this->customSuccess($results, "ارزیابی");
    }
}
