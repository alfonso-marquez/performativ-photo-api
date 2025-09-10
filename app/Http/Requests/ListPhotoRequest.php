<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ListPhotoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'photo_taken'    => 'date_format:Y-m-d',
            'sortBy'       => 'in:title,location,photo_category,camera_brand,photo_taken',
            'sortOrder'    => 'in:asc,desc',
        ];
    }

    public function messages(): array
    {
        return [
            'photo_taken.date_format'     => 'The photo taken must be in the format YYYY-MM-DD.',
            'sortBy.in'                   => 'The sortBy field must be one of: title, location, photo_category, camera_brand, photo_taken.',
            'sortOrder.in'                => 'The sortOrder field must be either asc or desc.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
