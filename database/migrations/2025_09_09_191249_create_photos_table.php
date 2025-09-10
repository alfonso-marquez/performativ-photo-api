<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('location', 255)->nullable();
            $table->string('photo_category')->nullable()->comment('e.g., Toy, Travel, Portrait');

            // Enum for camera brand
            $table->enum('camera_brand', [
                'Canon',
                'Sony',
                'Nikon',
                'Fujifilm',
                'Panasonic',
                'Olympus',
                'Leica',
                'Mobile',
                'Other',
            ])->nullable();

            $table->text('gear_used')->nullable(); // lens, filters, flash, etc.
            $table->string('photo_path', 255)->nullable();
            $table->date('photo_taken')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
