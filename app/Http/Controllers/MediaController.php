<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    //
     public function setMain(Media $media)
    {
        // شيل الرئيسي القديم
        $media->mediable->media()
            ->where('type', 'image')
            ->update(['is_primary' => false]);

        // حط الجديد
        $media->update([
            'is_primary' => true
        ]);

        return response()->json(['success' => true]);
    }


    public function order(Request $request)
    {

        foreach($request->images as $image)
        {

        Media::where('id',$image['id'])
        ->update([
        'sort_order'=>$image['position']
        ]);

        }


        return response()->json([
        'success'=>true
        ]);

    }

    public function destroy(Media $media)
    {
        Storage::disk('public')->delete($media->path);

        $media->delete();

        return response()->json(['success' => true]);
    }

}
