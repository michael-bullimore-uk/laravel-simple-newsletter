<?php

namespace App\Actions\Newsletter;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use MIBU\Newsletter\Events\Subscribed;
use MIBU\Newsletter\Models\Subscriber;
use MIBU\Newsletter\Rules\Email;
use MIBU\Newsletter\Rules\Honeypot;

class Subscribe
{
    public function exec(array $input): Subscriber
    {
        // I highly recommend implementing some form of spam prevention (honeypot, captcha etc.) validation here.
        $data = Validator::make($input, [
            'email' => [
                'required',
                ...Email::defaults(),
                // ...Honeypot::defaults(),
                'unique:'.config('newsletter.table_name'),
            ],
        ])->validateWithBag('newsletter'); // {{ $errors->newsletter->first('email') }}

        $data['token'] = Hash::make($plainTextToken = Str::random(), [
            'rounds' => 12,
        ]);
        $data['token'] = $plainTextToken;

        /** @var Subscriber $subscriber */
        $subscriber = app(config('newsletter.model'))::create($data);
        event(new Subscribed($subscriber)); // $plainTextToken

        return $subscriber;
    }
}
