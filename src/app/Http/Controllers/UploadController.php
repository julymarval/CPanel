<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function uploadSubmit(Request $request)
    {
        /*$images = [];
        foreach ($request->images as $image) {
            $filename = $image->store('photos');
            $product_photo = Image::create([
                'name' => $filename
            ]);
     
            $photo_object = new \stdClass();
            $photo_object->name = str_replace('photos/', '',$image->getClientOriginalName());
            $photo_object->size = round(Storage::size($filename) / 1024, 2);
            $photo_object->fileID = $Image->id;
            $images[] = $photo_object;
        }
     
        return response()->json(array('files' => $images), 200);*/
        $photos = [];
        foreach ($request->photos as $photo) {
            $filename = $photo->store('photos');
            $product_photo = ProductPhoto::create([
                'filename' => $filename
            ]);
     
            $photo_object = new \stdClass();
            $photo_object->name = str_replace('photos/', '',$photo->getClientOriginalName());
            $photo_object->size = round(Storage::size($filename) / 1024, 2);
            $photo_object->fileID = $product_photo->id;
            $photos[] = $photo_object;
        }
     
        return response()->json(array('files' => $photos), 200);
    }
     
    public function postProduct(Request $request)
    {
        dd("ok");
    }
}
