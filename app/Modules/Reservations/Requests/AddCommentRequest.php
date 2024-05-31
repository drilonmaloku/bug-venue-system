<?php

declare(strict_types=1);

namespace App\Modules\Reservations\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "reservations_id"=> "required",
            "comment" => "string",
        ];
    }

    public function messages(): array
    {
        return [
            // 'name.required' => 'The name field is required.',
            // 'email.email' => 'The email must be a valid email address.',
        ];
    }
}
