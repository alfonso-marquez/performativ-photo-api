<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Photo;
use App\Http\Requests\StorePhotoRequest;
use App\Http\Requests\UpdatePhotoRequest;

class PhotoController extends Controller
{

    public function index()
    {
        $photo = Photo::paginate(5);

        return response()->json([
            'status' => 'success',
            'data' => $photo,
        ]);
    }

    public function show($id)
    {
        try {
            $photo = Photo::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $photo,
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Photo not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    public function store(StorePhotoRequest  $request)
    {
        try {
            $data = $request->validated();
            $photo = Photo::create($data);

            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Photo created successfully',
                    'data' => $photo,
                ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create photo',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdatePhotoRequest $request, $id)
    {
        try {
            $photo = Photo::findOrFail($id);
            $data = $request->validated();

            $photo->update($data);

            return response()->json([
                'status'  => 'success',
                'message' => 'Photo details updated successfully',
                'data'    => $photo,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Photo not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $photo = Photo::findOrFail($id);
            $photo->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Photo deleted successfully',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Photo not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
            ], 500);
        }
    }
}
