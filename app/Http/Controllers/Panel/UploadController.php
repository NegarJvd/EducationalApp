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
                $path      = $file->storePubliclyAs('avatars', $filename);

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
                $path      = $file->storePubliclyAs('contents/content_covers', $filename);

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
                $path      = $file->storePubliclyAs('contents/' . $request->get('content_id'), $filename);

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
                $path      = $file->storePubliclyAs('contents/' . $request->get('content_id') . '/' . $request->get('cluster_id'), $filename);

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
                $path      = $file->storePubliclyAs('contents/' . $request->get('content_id') . '/' . $request->get('cluster_id'), $filename);

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
            $upload->delete();

            return $this->customSuccess(1, "فایل با موفقیت حذف شد.");

        }catch (Exception $exception){
            return $this->customError("فایل یافت نشد.");
        }
    }

    public function fetch_file($file_id){
        $file = File::find($file_id);
        return $this->customSuccess(ContentUploadResource::make($file), "اطلاعات فایل");
    }
}
