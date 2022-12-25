<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageProduct;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImgController extends Controller
{


    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $fileNameHash = Str::random(40) . '.' . $request->file('file')->getClientOriginalExtension();
            $filePath = $request->file('file')->storeAs('public', $fileNameHash);
            $dataUpload = [
                'file_name' => $fileNameHash,
                'file_path' => Storage::url($filePath)
            ];
            return $dataUpload;
        }
    }

    public function uploads(Request $request)
    {

        if ($request->hasFile('file')) {
            $arr = [];

            foreach ($request->file as $fileItem) {
                if ($fileItem) {
                    $fileNameHash = Str::random(40) . '.' . $fileItem->getClientOriginalExtension();
                    $filePath = $fileItem->storeAs('public', $fileNameHash);
                    $dataUploadTrait = [
                        'file_name' => $fileNameHash,
                        'file_path' => Storage::url($filePath)
                    ];
                    $arr[] = $dataUploadTrait;
                }
            }
            return $arr;
        } else {

            return 'oksd';
        }
    }

    public function delete(Request $request)
    {
        if ($request->name) {

            File::delete(public_path($request->name));

            return 'ok';
        }
    }
    public function removeProductImg($id)
    {

        $path = ImageProduct::find($id);

        if ($path->path) {
            File::delete(public_path($path->path));

            $path->delete();
            // Storage::delete(public_path($request->name));
            return 'ok';
        }
    }
}
