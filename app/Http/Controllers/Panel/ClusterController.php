<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Action;
use App\Models\Cluster;
use App\Models\Content;
use App\Models\File;
use App\Models\Step;
use Exception;
use Illuminate\Filesystem\Filesystem as FileSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
            'cover_id' => ['nullable', Rule::in(File::pluck('id'))]
        ]);

        $data = array_merge($request->only('name', 'description', 'cover_id'));

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
            'cover_id' => ['nullable', Rule::in(File::pluck('id'))]
        ]);

        $cluster->update($request->only('name', 'description', 'cover_id'));

        return back()->with('success', 'اطلاعات دسته بندی با موفقیت ویرایش شد.');
    }

    public function destroy(Content $content, Cluster $cluster){
        try{
            DB::beginTransaction();

            $steps_id_list = $cluster->steps()->pluck('id')->toArray();

            //deleting actions
            Action::whereIn('step_id', $steps_id_list)
                ->delete();

            //deleting contents user
            $content->users()->wherePivot('cluster_id', $cluster->id)->sync([]);

            //deleting steps
            $steps = Step::whereIn('id', $steps_id_list)->get();
            foreach ($steps as $step){
                $step->cover()->delete();
                $step->video()->delete();
            }
            Step::whereIn('id', $steps_id_list)->delete();

            //deleting directory of cluster
            $id = $cluster->id;
            $content_id = $cluster->content_id;
            (new FileSystem)->deleteDirectory(public_path('contents/'.$content_id.'/'.$id));

            //deleting cluster
            $cluster->delete();

            DB::commit();

            return redirect()->back()
                ->with('success', 'محتوا و تمام فایل ها و رکورد های مربوط به این محتوا حذف شد.');
        }catch (Exception $exception){
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'عملیات حذف با مشکل مواجه شده است.');
        }
    }
}
