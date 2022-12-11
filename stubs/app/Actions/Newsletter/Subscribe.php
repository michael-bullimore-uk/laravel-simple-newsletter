<?php

namespace App\Actions\Newsletter;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use MIBU\Newsletter\Models\Subscriber;

class Subscribe
{
    public function exec(array $input): Subscriber
    {
        // I highly recommend implementing some form of spam prevention validation (honeypot, captcha etc.) here.
        $data = Validator::make($input, [
            'email' => [
                'required',
                'email:rfc,dns', // https://laravel.com/docs/9.x/validation#rule-email
                'unique:'.config('newsletter.table_name'),
            ],
        ])->validateWithBag('newsletter'); // {{ $errors->newsletter->first('email') }}

        $data['token'] = Str::random(10);

        return app(config('newsletter.model'))::create($data);
    }
}
