<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Cluster;
use App\Models\Content;
use App\Models\Step;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UsersContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    function __construct()
    {
        $this->middleware('permission:content-list|add_content_for_user|delete_content_for_user');
        $this->middleware('permission:content-list', ['only' => ['get_each_contents_clusters_list', 'get_each_clusters_steps_list']]);
        $this->middleware('permission:add_content_for_user', ['only' => ['add_content_for_user']]);
        $this->middleware('permission:delete_content_for_user', ['only' => ['delete_content_for_user']]);
    }

    public function get_each_contents_clusters_list($content_id){
        $clusters = Cluster::where('content_id', $content_id)
            ->select('id', 'name')
            ->get();

        return $this->customSuccess($clusters, "لیست دسته بندی های محتوا");
    }

    public function get_each_clusters_steps_list($cluster_id){
        $steps = Step::where('cluster_id', $cluster_id)
            ->select('id', 'number')
            ->get();

        return $this->customSuccess($steps, "لیست مراحل هر دسته بندی");
    }

    public function add_content_for_user(Request $request){
        $request->validate([
            'content_id' => ['required', Rule::in(Content::pluck('id'))],
            'cluster_id' => ['required', Rule::in(Cluster::where('content_id', $request->get('content_id'))->pluck('id'))],
            'user_id' => ['required', Rule::in(User::where('admin_id', Auth::id())->pluck('id'))]
        ]);

        $content_id = $request->get('content_id');
        $cluster_id = $request->get('cluster_id');
        $user_id = $request->get('user_id');

        $user = User::find($user_id);

        if(in_array($content_id, $user->contents()->pluck('id')->toArray())){
            return $this->customError("این محتوا قبلا برای کاربر فعال شده است.");
        }

        $user->contents()
            ->attach($content_id, ['cluster_id' => $cluster_id]);

        $content_name = Content::find($content_id)->name;
        $cluster = Cluster::find($cluster_id);
        $cluster_name = $cluster->name;
        $steps_count = $cluster->steps()->count();

        $data = [
            'content_name' => $content_name,
            'cluster_name' => $cluster_name,
            'cluster_id' => $cluster_id,
            'steps_count' => $steps_count
        ];

        return $this->customSuccess($data, "محتوا با موفقیت برای کاربر فعال شد.");
    }

    public function delete_content_for_user(Request $request){
        $request->validate([
            'cluster_id' => ['required', Rule::in(Cluster::pluck('id'))],
            'user_id' => ['required', Rule::in(User::where('admin_id', Auth::id())->pluck('id'))]
        ]);

        $cluster_id = $request->get('cluster_id');
        $content_id = Cluster::find($cluster_id)->content_id;
        $user_id = $request->get('user_id');

        $user = User::find($user_id);

        $user->contents()
            ->detach($content_id, ['cluster_id' => $cluster_id]);

        return $this->customSuccess(1, "محتوا با موفقیت برای کاربر غیر فعال شد.");
    }
}
