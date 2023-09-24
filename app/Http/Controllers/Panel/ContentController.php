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

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    function __construct()
    {
        $this->middleware('permission:content-list|content-create|content-edit|content-delete');
        $this->middleware('permission:content-list', ['only' => ['index']]);
        $this->middleware('permission:content-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:content-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:content-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request){
        $contents = Content::query()
                            ->withCount('clusters')
                            ->withCount('users');

        $search = $request->get('search');
        if (!is_null($search)){
            $contents = $contents->where(function ($query) use($search){
                $query->where('name', 'LIKE', "%$search%");
            });
        }

        $contents = $contents->orderByDesc('id')
                        ->paginate($this->perPagePanel);

        return view('panel.contents.index', compact('contents'));
    }

    public function create(){
        return view('panel.contents.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'cover_id' => ['nullable', Rule::in(File::pluck('id'))]
        ]);

        $content = Content::create($request->only('name', 'cover_id'));

        return redirect()->route('panel.contents.edit', $content->id)
                        ->with('success', 'محتوا با موفقیت ایجاد شد.');
    }

    public function edit(Content $content){
        $clusters = $content->clusters()->paginate($this->perPagePanel);
//        $search = $request->get('search');
//        if (!is_null($search)){
//            $clusters = $clusters->where(function ($query) use($search){
//                $query->where('name', 'LIKE', "%$search%");
//            });
//        }

        return view('panel.contents.edit', compact('content', 'clusters'));
    }

    public function update(Request $request, Content $content){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'cover_id' => ['nullable', Rule::in(File::pluck('id'))]
        ]);

        $content->update($request->only('name', 'cover_id'));

        return back()->with('success', 'اطلاعات محتوا با موفقیت ویرایش شد.');
    }

    public function destroy(Content $content){
        try {
            DB::beginTransaction();
            $clusters_id_list = $content->clusters()->pluck('id')->toArray();
            $steps_id_list = Step::whereIn('cluster_id', $clusters_id_list)->pluck('id')->toArray();

            //deleting actions
            Action::whereIn('step_id', $steps_id_list)
                ->delete();

            //deleting contents user
            $content->users()->sync([]);

            //deleting steps
            $steps = Step::whereIn('id', $steps_id_list)->get();
            foreach ($steps as $step) {
                $step->cover()->delete();
                $step->video()->delete();
            }
            Step::whereIn('id', $steps_id_list)->delete();

            //deleting clusters
            $clusters = Cluster::whereIn('id', $clusters_id_list)->get();
            foreach ($clusters as $cluster) {
                $cluster->cover()->delete();
            }
            Cluster::whereIn('id', $clusters_id_list)->delete();

            //deleting files
            $id = $content->id;
            (new FileSystem)->deleteDirectory(public_path('contents/' . $id));

            //delete content
            $content->delete();

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
