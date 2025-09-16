<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Photo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PhotoControllerTest extends TestCase
{

    use RefreshDatabase;

    public function test_it_can_list_photos()
    {
        Photo::factory()->count(3)->create();

        $response = $this->getJson('/api/photos');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data' => ['data', 'current_page', 'last_page', 'per_page', 'total']
                 ]);
    }

    public function test_it_can_show_a_photo()
    {
        $photo = Photo::factory()->create();

        $response = $this->getJson("/api/photos/{$photo->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'data' => [
                         'id' => $photo->id,
                         'title' => $photo->title
                     ]
                 ]);
    }

    public function test_it_returns_404_if_photo_not_found()
    {
        $response = $this->getJson('/api/photos/999');

        $response->assertStatus(404)
                 ->assertJson(['status' => 'error']);
    }

    public function test_it_can_create_a_photo()
    {
        $storage = Storage::fake('public');


        $file = UploadedFile::fake()->image('photo.jpg');

        $response = $this->postJson('/api/photos', [
            'title' => 'Test Photo',
            'description' => 'Test description',
            'location' => 'Test location',
            'photo_category' => 'Nature',
            'camera_brand' => 'Sony',
            'gear_used' => 'Lens 50mm',
            'photo_path' => $file,
        ]);

        $response->assertStatus(201)
                 ->assertJson(['status' => 'success']);

         /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');


        // Assert the file exists on fake storage
        $disk->assertExists('photos/' . $file->hashName());

        // Assert the database has the photo
        $this->assertDatabaseHas('photos', ['title' => 'Test Photo']);
    }

    public function test_it_can_update_a_photo()
    {
        Storage::fake('public');

        $photo = Photo::factory()->create(['title' => 'Old Title']);

        $file = UploadedFile::fake()->image('newphoto.jpg');

        $response = $this->putJson("/api/photos/{$photo->id}", [
            'title' => 'Updated Title',
            'photo_path' => $file
        ]);

        $response->assertStatus(200)
                 ->assertJson(['status' => 'success']);

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        // Assert the file exists on fake storage
        $disk->assertExists('photos/' . $file->hashName());

        // Assert the database was updated
        $this->assertDatabaseHas('photos', ['id' => $photo->id, 'title' => 'Updated Title']);
    }

    public function test_it_can_delete_a_photo()
    {
        $photo = Photo::factory()->create();

        $response = $this->deleteJson("/api/photos/{$photo->id}");

        $response->assertStatus(200)
                 ->assertJson(['status' => 'success']);

        $this->assertSoftDeleted('photos', ['id' => $photo->id]);
    }
}
