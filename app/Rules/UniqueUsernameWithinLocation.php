<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueUsernameWithinLocation implements ValidationRule
{
    protected $locationSlug;

    public function __construct()
    {
        $this->locationSlug =  auth()->user()->getCurrentLocationSlug();
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Create the username with the location slug
        $usernameWithSlug = $this->locationSlug . '_' . $value;

        // Check if the username with slug exists in the users table
        if (DB::table('users')->where('username', $usernameWithSlug)->exists()) {
            $fail("The {$attribute} has already been taken for the current location.");
        }
    }
}
