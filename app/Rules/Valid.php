<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class Valid implements Rule
{

    public function passes($attribute, $value)
    {
        $response = Http::withHeaders([
            'x-api-key' => env('BOUNCER_API_KEY')
        ])->get('https://api.usebouncer.com/v1/email/verify?email='.$value.'&timeout=10');

        return $response->json('status') === 'deliverable';
    }

    public function message()
    {
        return 'Please enter a valid email address';
    }
}
