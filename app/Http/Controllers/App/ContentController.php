<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActiveContentResource;
use App\Http\Resources\StepResource;
use App\Http\Resources\ContentResource;
use App\Models\Cluster;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ContentController extends Controller
{
    public function contents_list(Request $request){
        $request->validate([
            'is_active' => ['nullable', Rule::in([0, 1])]
        ]);

        if($request->get('is_active') == 1)
            return $this->customSuccess(ActiveContentResource::collection(Auth::user()->contents()->orderBy('id', 'asc')->get()), "لیست محتوا های فعال");
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
}
