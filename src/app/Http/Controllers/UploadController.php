<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Image;
use App\Volunteer;
use App\Show;
use App\Sponsor;

class UploadController extends Controller
{
    public function Event(Request $request)
    {
        $photos = [];
        foreach ($request->photos as $photo) {
            $filename = $photo -> getClientOriginalName();
            $path = public_path() . '/images/events/';
            $photo -> move($path,$filename);
            $product_photo =  new Image();
            $product_photo -> name = $filename;

            $product_photo -> save();
                        
            $photo_object = new \stdClass();
            $photo_object->name = $photo->getClientOriginalName();
            $photo_object->size = round(filesize(public_path() . '/images/events/' . $filename) / 1024, 2);
            $photo_object->fileID = $product_photo->id;
            $photos[] = $photo_object;
        }
     
        return response()->json(array('files' => $photos), 200);
    }

    public function Volunteer(Request $request)
    {
        $photos = [];
        foreach ($request->photos as $photo) {
            $filename = $photo -> getClientOriginalName();
            $path = public_path() . '/images/volunteers/';
            $photo -> move($path,$filename);
            $photo_object = new \stdClass();
            $photo_object->name = $photo->getClientOriginalName();
            $photo_object->size = round(filesize(public_path() . '/images/volunteers/' . $filename)/ 1024, 2);
            $photos[] = $photo_object;
        }
     
        return response()->json(array('files' => $photos), 200);
    }

    public function Sponsor(Request $request)
    {
        $photos = [];
        foreach ($request->photos as $photo) {
            $filename = $photo -> getClientOriginalName();
            $path = public_path() . '/images/sponsors/';
            $photo -> move($path,$filename);
            $photo_object = new \stdClass();
            $photo_object->name = $photo->getClientOriginalName();
            $photo_object->size = round(filesize(public_path() . '/images/sponsors/' . $filename) / 1024, 2);
            $photos[] = $photo_object;
        }
     
        return response()->json(array('files' => $photos), 200);
    }

    public function Show(Request $request)
    {
        $photos = [];
        foreach ($request->photos as $photo) {
            $filename = $photo -> getClientOriginalName();
            $path = public_path() . '/images/shows/';
            $photo -> move($path,$filename);
            $photo_object = new \stdClass();
            $photo_object->name = $photo->getClientOriginalName();
            $photo_object->size = round(filesize(public_path() . '/images/shows/' . $filename) / 1024, 2);
            $photos[] = $photo_object;
        }
     
        return response()->json(array('files' => $photos), 200);
    }

    public function Home(Request $request)
    {
        $rules = [
            'file' => 'required|max:10000', 
        ];
        $validator = \Validator::make($request->all(), $rules);
        
        try{
            if ($validator->fails()) {
                
                flash('The image is to big. Try another one.') -> error();
                return redirect()->back();
            }

            if ($request->file('file')) {
                $image = $request->file('file');
                $name = 'home.jpg';
                $destinationPath = public_path('/images');
                $image->move($destinationPath, $name);
                
                flash('Home Image Uploaded Successfully.') -> success();
                return redirect()->back();
            }
        }catch (Exception $e) {
            \Log::info('Error updating home image: '.$e);

            $path = public_path() . '/images/home.jpg';
            \Storage::delete($path);
            
            flash('Ops! An error has ocurred. Please try again.') -> error();
            return redirect() -> back();
        }
    }
     
}
