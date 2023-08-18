<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Cluster;
use App\Models\Content;
use App\Models\File;
use App\Models\Step;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StepController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    function __construct()
    {
        $this->middleware('permission:content-create|content-edit|content-delete');
        $this->middleware('permission:content-create', ['only' => ['store']]);
        $this->middleware('permission:content-edit', ['only' => ['update']]);
        $this->middleware('permission:content-delete', ['only' => ['destroy']]);
    }

    public function store(Request $request, Content $content, Cluster $cluster){
        $request->validate([
            'description' => ['required', 'string'],
            'cover_id' => ['nullable', Rule::in(File::pluck('id'))],
            'video_id' => ['required', Rule::in(File::pluck('id'))],
        ]);

        $data = array_merge($request->only('description', 'cover_id', 'video_id'), ['number' => $cluster->steps()->count() + 1]);

        $cluster->steps()->create($data);

        return redirect()->back()
            ->with('success', 'مرحله بعدی با موفقیت اضافه شد.');
    }

    public function update(Request $request, Content $content, Cluster $cluster, Step $step){
        $request->validate([
            'description' => ['required', 'string'],
            'cover_id' => ['nullable', Rule::in(File::pluck('id'))],
            'video_id' => ['required', Rule::in(File::pluck('id'))],
        ]);

        $step->update($request->only('description', 'cover_id', 'video_id'));

        return back()->with('success', 'مرحله با موفقیت ویرایش شد.');
    }

    public function destroy(Content $content, Cluster $cluster, Step $step){
        //deleting actions
        $step->actions()->delete();

        //deleting step
        $step->delete();

        //update other steps numbers
        $steps = $cluster->steps()->orderBy('id')->get();
        foreach ($steps as $i=>$s){
            $s->update(['number' => $i+1]);
        }

        return redirect()->back()
            ->with('success', 'تمام فایل ها و رکورد های مربوط به این مرحله حذف شد.');
    }
}
