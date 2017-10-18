<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function uploadSubmit(Request $request)
    {
        $images = [];
        $files = $request -> file('images');  
        foreach($files as $file){
        
            $name = $file -> getClientOriginalName();
            $path = public_path() . '/images/events/';
            $file -> move($path,$name);

            $image = new Image();
            $image -> name = $name;
            $image -> save();

            $photo_object = new \stdClass();
            $photo_object->name = str_replace('photos/', '',$file->getClientOriginalName());
            $photo_object->size = round(Storage::size($filename) / 1024, 2);
            $photo_object->fileID = $image->id;
            $images[] = $photo_object;
        }
     
        return response()->json(array('file' => $images), 200);
    }
     
    public function postProduct(Request $request)
    {
        // This method will cover whole product submit
    }
}
