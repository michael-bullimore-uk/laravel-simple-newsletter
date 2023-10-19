<?php

namespace App\Actions\Newsletter;

use Illuminate\Support\Facades\Validator;
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
        ])->validateWithBag(config('newsletter.error_bag')); // {{ $errors->newsletter->first('email') }}

        $model = config('newsletter.model');

        /** @var null|Subscriber $subscriber */
        $subscriber = $model::email($data['email'])->first();
        if (! $subscriber) {
            /** @var Subscriber $subscriber */
            $subscriber = app($model)::create($data);

            event(new Subscribed($subscriber));
        }

        return $subscriber;
    }
}
