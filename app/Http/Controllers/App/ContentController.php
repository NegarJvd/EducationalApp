<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Resources\StepResource;
use App\Http\Resources\ContentResource;
use App\Models\Cluster;
use App\Models\Content;
use App\Models\Step;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ContentController extends Controller
{
    public function contents_list(){
        return $this->customSuccess(ContentResource::collection(Content::all()), "لیست محتوا ها");
    }

    public function cluster_detail($content_id){
        $user = Auth::user();
        if(!in_array($content_id, $user->contents()->pluck('id')->toArray())){
            return $this->customError("این محتوا برای شما فعال نیست.");
        }

        $content = $user->contents()->find($content_id);
        $cluster_id = $content->pivot->cluster_id;
        $cluster = Cluster::find($cluster_id);
        if (!$cluster) return $this->customError("یافت نشد.", 404);

        $steps = $cluster->steps()->orderBy('number')->get();

        return $this->customSuccess(StepResource::collection($steps), "مراحل محتوای ". $content->name);
    }

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
}
