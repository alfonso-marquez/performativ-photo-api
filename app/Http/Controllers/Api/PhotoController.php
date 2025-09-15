<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Photo;
use App\Http\Requests\StorePhotoRequest;
use App\Http\Requests\UpdatePhotoRequest;
use App\Http\Requests\ListPhotoRequest;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{

    public function index(ListPhotoRequest $request)
    {

        // $photo = Photo::paginate(5);
        $photos = Photo::query()
            //Global search
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('photo_category', 'like', "%{$search}%")
                        ->orWhere('camera_brand', 'like', "%{$search}%")
                        ->orWhere('gear_used', 'like', "%{$search}%");
                });
            })
            // Filter by title (search by photo name)
            ->when($request->filled('title'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->title . '%');
            })
            // Filter by location (search by where photo was taken)
            ->when($request->filled('location'), function ($query) use ($request) {
                $query->where('location', 'like', '%' . $request->location . '%');
            })
            // Filter by category
            ->when($request->filled('photo_category'), function ($query) use ($request) {
                $query->where('photo_category', $request->photo_category);
            })
            // Filter by camera brand
            ->when($request->filled('cameraBrand'), function ($query) use ($request) {
                $query->where('camera_brand', $request->camera_brand);
            })
            // Filter by gear used (like lens type)
            ->when($request->filled('gearUsed'), function ($query) use ($request) {
                $query->where('gear_used', 'like', '%' . $request->gear_used . '%');
            })
            // Conditional ordering
            ->when(
                $request->filled('sortBy') && $request->filled('sortOrder'),
                function ($query) use ($request) {
                    if (!in_array($request->sortOrder, ['asc', 'desc'])) {
                        return;
                    }
                    $query->orderBy($request->sortBy, $request->sortOrder)
                        ->orderBy('created_at', 'desc');
                },
                // Default order
                function ($query) {
                    $query->orderBy('created_at', 'desc')
                        ->orderBy('photo_taken', 'desc');
                }
            )
            ->paginate(5);


        return response()->json([
            'status' => 'success',
            'data' => $photos,
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

            // Handle photo upload
            if ($request->hasFile('photo_path')) {
                $data['photo_path'] = $request->file('photo_path')->store('photos', 'public');
            } else {
                $data['photo_path'] = "default photo path"; // no file uploaded
            }


            $photo = Photo::create($data);

            // Storage::url to get the full URL of the stored photo
            $photo->photo_path = $photo->photo_path ? Storage::url($photo->photo_path) : null;

            return response()->json([
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

            // Debug: see what data Laravel actually received
                // dd([
                //     'validated_data' => $data,
                //     'photo_before_update' => $photo
                // ]);

            // Handle file replacement
            if ($request->hasFile('photo_path')) {
                // Delete old photo if exists
                if ($photo->photo_path && Storage::disk('public')->exists($photo->photo_path)) {
                    Storage::disk('public')->delete($photo->photo_path);
                }
                // Store new file
                $data['photo_path'] = $request->file('photo_path')->store('photos', 'public');
            }

            $photo->update($data);
            // dd($photo);
            // Using fill() instead of update() to avoid overwriting fields with null
            // $photo->fill($data);
            // $photo->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Photo details updated successfully',
                'data'    => $photo,
            ], 200);
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
