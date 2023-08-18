<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Action;
use App\Models\Cluster;
use App\Models\Content;
use App\Models\Step;
use Illuminate\Http\Request;

class ClusterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    function __construct()
    {
        $this->middleware('permission:content-create|content-edit|content-delete');
        $this->middleware('permission:content-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:content-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:content-delete', ['only' => ['destroy']]);
    }

    public function create(Content $content){
        return view('panel.clusters.create', compact('content'));
    }

    public function store(Request $request, Content $content){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        //TODO
        $data = array_merge($request->only('name', 'description'), ['cover_id' => 1]);

        $cluster = $content->clusters()
                            ->create($data);

        return redirect()->route('panel.contents.clusters.edit', [$content->id,  $cluster->id])
            ->with('success', 'دسته بندی با موفقیت ایجاد شد.');
    }

    public function edit(Content $content, Cluster $cluster){
        $steps = $cluster->steps()->orderBy('number')->paginate($this->perPagePanel);

        return view('panel.clusters.edit', compact('content', 'cluster', 'steps'));
    }

    public function update(Request $request, Content $content, Cluster $cluster){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        $cluster->update($request->only('name', 'description'));

        return back()->with('success', 'اطلاعات دسته بندی با موفقیت ویرایش شد.');
    }

    public function destroy(Content $content, Cluster $cluster){
        $steps_id_list = $cluster->steps()->pluck('id')->toArray();

        //deleting actions
        Action::whereIn('step_id', $steps_id_list)
            ->delete();

        //deleting contents user
        $content->users()->wherePivot('cluster_id', $cluster->id)->sync([]);

        //deleting steps
        Step::whereIn('id', $steps_id_list)->delete();

        //deleting cluster
        $cluster->delete();

        return redirect()->back()
            ->with('success', 'محتوا و تمام فایل ها و رکورد های مربوط به این محتوا حذف شد.');
    }
}
