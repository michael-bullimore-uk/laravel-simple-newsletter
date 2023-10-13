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
                // 'unique:'.config('newsletter.table_name'), // Let's not expose existence of an email addy.
            ],
            // 'topyenoh' => Honeypot::defaults(),
        ])->validateWithBag('newsletter'); // {{ $errors->newsletter->first('email') }}

        $data['token'] = Str::random(16); // Explicit length

        $model = config('newsletter.model');
        /** @var null|Subscriber $subscriber */
        $subscriber = $model::email($data['email'])->first();
        if (! $subscriber) {
            /** @var Subscriber $subscriber */
            $subscriber = app($model)::create($data);
            event(new Subscribed($subscriber, $data['token']));
        }

        return $subscriber;
    }
}
