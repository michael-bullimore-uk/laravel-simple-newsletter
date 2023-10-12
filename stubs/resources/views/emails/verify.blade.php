<x-mail::message>
# Verify
{{ $subscriber->email }}
<x-mail::button :url="route('newsletter.verify', [
    'id' => $subscriber->id,
    'token' => $subscriber->token,
])">
Verify
</x-mail::button>
</x-mail::message>
