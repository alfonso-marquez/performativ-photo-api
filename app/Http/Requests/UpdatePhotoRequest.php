<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePhotoRequest extends FormRequest
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
            'title'          => 'sometimes|required|string|max:255',
            'description'    => 'nullable|string',
            'location'       => 'nullable|string|max:255',
            'photo_category' => 'nullable|string|max:255',
            'camera_brand'   => 'nullable|string|max:100',
            'gear_used'      => 'nullable|string',
            'photo_path'     => 'nullable|string|max:255',
            'photo_taken'    => 'nullable|date_format:Y-m-d|before_or_equal:tomorrow',
        ];
    }


    /**
     * Custom messages for validation errors.
     */

    public function messages(): array
    {
        return [
            'title.required'       => 'Photo title is required.',
            // 'photo_path.required'         => 'Photo path is required',
            'photo_taken.date_format'     => 'The photo taken must be in the format YYYY-MM-DD.',
            'photo_taken.before_or_equal' => 'The photo taken date cannot be in the future.',
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
