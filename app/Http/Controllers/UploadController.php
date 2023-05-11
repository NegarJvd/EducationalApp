<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UploadController extends Controller
{

    public function upload_file(Request $request){
        $request->validate([
            'file' => 'file|required',
            'type' => 'required|in:avatar'
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
                //$path      = $file->storeAs('avatars', $filename);
                //------------------------------------------------------------------------------
                $file->move(create_basic_dir()['avatars_files']['public_path'] , $filename);
                $path = create_basic_dir()['avatars_files']['path'] . '/' . $filename;
                //------------------------------------------------------------------------------
                $original_name = $file->getClientOriginalName();

                $upload = Upload::create([
                    'path' => $path,
                    'original_name' => $original_name
                ]);

                $user = Auth::user();
                $user->avatar_id = $upload->id;
                $user->save();

                return $this->success($upload->id, "فایل پروفایل با موفقیت آپلود شد.");

            default:
                return $this->customError("نوع فایل درست انتخاب نشده است.");
        }

    }

    public static function delete($upload_id, $multi = 0){
        if ($multi == 1){
            if (!is_array($upload_id)){
                return false;
            }

            foreach ($upload_id as $item){
                $upload = Upload::find($item);

                try {
                    $path = $upload->path;

                    if (file_exists(public_path($path))){
                        unlink(public_path($path));
                    }

                    if (file_exists($path)){
                        unlink($path);
                    }

                    $upload->delete();
                }catch (Exception $exception){
                    continue;
                }
            }

            return true;

        }else{
            $upload = Upload::find($upload_id);

            try {
                $path = $upload->path;

                if (file_exists(public_path($path))){
                    unlink(public_path($path));
                }

                if (file_exists($path)){
                    unlink($path);
                }

                if ($upload->user){
                    $user = $upload->user()->first();
                    $user->avatar_id = null;
                    $user->save();
                }

                if ($upload->admin){
                    $admin = $upload->admin()->first();
                    $admin->avatar_id = null;
                    $admin->save();
                }

                if ($upload->product){
                    $product = $upload->product()->first();
                    $product->image_id = null;
                    $product->save();
                }

                if ($upload->product_cover){
                    $product2 = $upload->product_cover()->first();
                    $product2->cover_image_id = null;
                    $product2->save();
                }

                $upload->delete();

                return true;

            }catch (Exception $exception){
                return false;
            }
        }
    }

    public function delete_file($upload_id, $multi = 0){
        if ($multi == 1 and !is_array($upload_id)){
            return $this->customError("فرمت ارسالی شناسه فایل ها باید ارایه باشد.");
        }

        $delete = self::delete($upload_id, $multi);
        if($delete) {
            return $this->success($delete, "فایل با موفقیت حذف شد.");
        }else{
            return $this->customError("فایل یافت نشد.");
        }
    }
}
