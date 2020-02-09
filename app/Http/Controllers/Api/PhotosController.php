<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Http\Resources\PhotoResource;

class PhotosController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'entity_id' => 'required',
            'entity_type' => 'required',
            'photo' => ['required', 'image'],
            'photo_id' => ['sometimes', 'exists:photos,id']
        ]);

        if ($request->has('photo_id')) {
            $photo = Photo::find($request->photo_id);
        } else {
            $entity = $request->entity_type::find($request->entity_id);
            $photo = $entity->photo()->create();
        }

        $image = new \claviska\SimpleImage();
        $image
            ->fromFile($request->photo)
            ->resize(300, null)
            ->toFile($photo->path, 'image/jpeg', 70);

        return new PhotoResource($photo);
    }

    public function destroy(Photo $photo)
    {
        unlink($photo->path);
        $photo->delete();
    }
}
