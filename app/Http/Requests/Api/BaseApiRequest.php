<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class BaseApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    abstract public function authorize(): bool;

    /**
     * Get the validation rules that apply to the request
     */
    abstract public function rules(): array;

    /**
     * Handle a failed validation attempt
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'details' => $validator->errors(),
                ],
                'meta' => [
                    'timestamp' => now()->toIso8601String(),
                    'request_id' => $this->header('X-Request-ID', uniqid('req_')),
                ],
            ], 422)
        );
    }

    /**
     * Handle a failed authorization attempt
     */
    protected function failedAuthorization(): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'error' => [
                    'code' => 'UNAUTHORIZED',
                ],
                'meta' => [
                    'timestamp' => now()->toIso8601String(),
                    'request_id' => $this->header('X-Request-ID', uniqid('req_')),
                ],
            ], 403)
        );
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'required' => 'The :attribute field is required.',
            'email' => 'The :attribute must be a valid email address.',
            'unique' => 'The :attribute has already been taken.',
            'date' => 'The :attribute must be a valid date.',
            'date_format' => 'The :attribute must match the format :format.',
            'numeric' => 'The :attribute must be a number.',
            'integer' => 'The :attribute must be an integer.',
            'string' => 'The :attribute must be a string.',
            'array' => 'The :attribute must be an array.',
            'boolean' => 'The :attribute must be true or false.',
            'min' => 'The :attribute must be at least :min.',
            'max' => 'The :attribute may not be greater than :max.',
            'in' => 'The selected :attribute is invalid.',
            'exists' => 'The selected :attribute is invalid.',
        ];
    }

    /**
     * Get custom attributes for validator errors
     */
    public function attributes(): array
    {
        return [];
    }
}
