<?php

namespace Database\Factories;

use App\Models\Photo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Photo>
 */
class PhotoFactory extends Factory
{
    protected $model = Photo::class;

    public function definition(): array
    {

        $cameraBrands = ['Canon', 'Sony', 'Nikon', 'Fujifilm', 'Panasonic', 'Olympus', 'Leica', 'Mobile', 'Other'];
        $photoCategories = ['Toy', 'Travel', 'Portrait', 'Landscape', 'Street', 'Event', 'Wildlife', 'Other'];

        $gearOptions = [
            'Canon 50mm f/1.8',
            'Tamron 17-70mm f/2.8',
            'Sony 85mm f/1.4',
            'Fujifilm XF 23mm f/1.4',
            'Tripod',
            'ND Filter',
            'Flash',
            'Reflector',
            'Gimbal',
            'Godox Retro Flash',
        ];

        $gearUsed = $this->faker->randomElements($gearOptions, rand(1, 3));


        // Fetch random cat image from The Cat API
        $baseUrl = config('services.cat.base'); // or env('CAT_API_BASE')

        $response = Http::withHeaders([
            'x-api-key' => config('services.cat.key'),
        ])->get("{$baseUrl}/images/search", [
            'size' => 'med',
            'mime_types' => 'jpg',
            'has_breeds' => 'true',
            'order' => 'RANDOM',
            'page' => 0,
            'limit' => 1,
        ]);



        // Get the first element from the API response array
        $json = $response->successful() ? ($response->json()[0] ?? null) : null;
        $photoUrl = $json['url'] ?? 'https://placehold.co/600x400.png';
        $catImageUrl = $response->successful() ? $response->json()[0]['url'] : $photoUrl; // add default place holder backup

        // Use breed name if available, otherwise fallback to Faker
        // $title = isset($json['breeds'][0]['name']) ? $json['breeds'][0]['name'] : $this->faker->sentence(3);
        $title = isset($json['breeds'][0]['name']) ? $json['breeds'][0]['name'] : $this->faker->sentence(3);

        return [
            'title' => $title,
            'description' => $this->faker->randomElement([
                'A curious cat photo just chilling here as a placeholder. Replace me with a real masterpiece!',
                'This cat is temporarily taking your spot. Update with your own photo when ready!',
                'Feline fine as a placeholder. Swap me out for a real picture!',
                'Just a cat doing cat things while we wait for your real photo.',
                'Purr-fect placeholder! Do not forget to replace me with a proper image.'
            ]),
            'location' => $this->faker->city() . ', ' . $this->faker->country(),
            'photo_category' => $this->faker->randomElement($photoCategories),
            'camera_brand' => $this->faker->randomElement($cameraBrands),
            'gear_used' => implode(', ', $gearUsed),
            'photo_path' => $catImageUrl, // Store the real cat image URL as placeholder
            'photo_taken' => $this->faker->dateTimeBetween('-3 years', 'now')->format('Y-m-d'),
        ];
    }
}
