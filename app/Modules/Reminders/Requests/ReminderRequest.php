<?php

namespace App\Modules\Reminders\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReminderRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'reminder_date' => 'required|date|after:now',
            'type' => 'required|in:in_app,email,both',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The reminder title is required.',
            'title.max' => 'The reminder title cannot exceed 255 characters.',
            'message.required' => 'The reminder message is required.',
            'reminder_date.required' => 'The reminder date is required.',
            'reminder_date.after' => 'The reminder date must be in the future.',
            'type.required' => 'The reminder type is required.',
            'type.in' => 'The reminder type must be either in-app, email, or both.',
        ];
    }
} 