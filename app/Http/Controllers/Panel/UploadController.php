<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContentUploadResource;
use App\Models\Cluster;
use App\Models\Content;
use App\Models\File;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UploadController extends Controller
{
    public function upload_file(Request $request){
        $request->validate([
            'file' => 'file|required',
            'type' => 'required|in:avatar,content_cover,cluster_cover,step_cover,step_video'
        ]);

        $type =  $request->get('type');

        switch ($type){
            case "avatar":
                $validation = $request->validate([
                    'file' => 'file|required|image',
                ]);

                $file      = $validation['file']; // get the validated file
                $extension = $file->getClientOriginalExtension();
                $filename  = 'profile-photo-' . time() . '.' . $extension;
                $path      = $file->storeAs('avatars', $filename);

                $upload = File::create([
                    'file_path' => $path
                ]);

                $user = Auth::user();
                $user->avatar_id = $upload->id;
                $user->save();

                return $this->customSuccess($upload->id, "فایل پروفایل با موفقیت آپلود شد.");

            case "content_cover":
                $validation = $request->validate([
                    'file' => 'file|required|image',
                ]);

                $file      = $validation['file']; // get the validated file
                $extension = $file->getClientOriginalExtension();
                $filename  = 'content-cover-' . time() . '.' . $extension;
                $path      = $file->storeAs('contents/content_covers', $filename);

                $file = File::create([
                    'file_path' => $path,
                ]);

                return $this->customSuccess(ContentUploadResource::make($file), "فایل محتوا با موفقیت آپلود شد.");

            case "cluster_cover":
                $validation = $request->validate([
                    'content_id' => ['required', Rule::in(Content::pluck('id'))],
                    'file' => 'file|required|image',
                ]);

                $file      = $validation['file']; // get the validated file
                $extension = $file->getClientOriginalExtension();
                $filename  = 'cluster-cover-' . time() . '.' . $extension;
                $path      = $file->storeAs('contents/' . $request->get('content_id'), $filename);

                $file = File::create([
                    'file_path' => $path,
                ]);

                return $this->customSuccess(ContentUploadResource::make($file), "فایل محتوا با موفقیت آپلود شد.");

            case "step_cover":
                $validation = $request->validate([
                    'content_id' => ['required', Rule::in(Content::pluck('id'))],
                    'cluster_id' => ['required', Rule::in(Cluster::pluck('id'))],
                    'file' => 'file|required|image',
                ]);

                $file      = $validation['file']; // get the validated file
                $extension = $file->getClientOriginalExtension();
                $filename  = 'step-cover-' . time() . '.' . $extension;
                $path      = $file->storeAs('contents/' . $request->get('content_id') . '/' . $request->get('cluster_id'), $filename);

                $file = File::create([
                    'file_path' => $path,
                ]);

                return $this->customSuccess(ContentUploadResource::make($file), "فایل محتوا با موفقیت آپلود شد.");

            case "step_video":
                $validation = $request->validate([
                    'content_id' => ['required', Rule::in(Content::pluck('id'))],
                    'cluster_id' => ['required', Rule::in(Cluster::pluck('id'))],
                    'file' => 'file|required|mimetypes:video/mp4',
                ]);

                $file      = $validation['file']; // get the validated file
                $extension = $file->getClientOriginalExtension();
                $filename  = 'step-video-' . time() . '.' . $extension;
                $path      = $file->storeAs('contents/' . $request->get('content_id') . '/' . $request->get('cluster_id'), $filename);

                $file = File::create([
                    'file_path' => $path,
                ]);

                return $this->customSuccess(ContentUploadResource::make($file), "فایل محتوا با موفقیت آپلود شد.");

            default:
                return $this->customError("نوع فایل درست انتخاب نشده است.");
        }

    }

    public function delete_file($upload_id){
        $upload = File::find($upload_id);

        try {
            $path = $upload->file_path;

            if (file_exists($path)){
                unlink($path);
            }

//            if ($upload->user){
//                $user = $upload->user()->first();
//                $user->avatar_id = null;
//                $user->save();
//            }

            if ($upload->content){
                $content = $upload->content()->first();
                $content->cover_id = null;
                $content->save();
            }

            if ($upload->cluster){
                $cluster = $upload->cluster()->first();
                $cluster->cover_id = null;
                $cluster->save();
            }

            if ($upload->step_cover){
                $step_cover = $upload->step_cover()->first();
                $step_cover->cover_id = null;
                $step_cover->save();
            }

            if ($upload->step_video){
                $step_video = $upload->step_video()->first();
                $step_video->video_id = null;
                $step_video->save();
            }

            $upload->delete();

            return $this->customSuccess(1, "فایل با موفقیت حذف شد.");

        }catch (Exception $exception){
            return $this->customError("فایل یافت نشد.");
        }
    }
}
