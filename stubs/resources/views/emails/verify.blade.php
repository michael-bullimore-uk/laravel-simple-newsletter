{{-- Markdown indentation --}}
<x-mail::message>
# Verify
{{ $subscriber->email }}
<x-mail::button :url="route('newsletter.verify', [
    'id' => $subscriber->id,
    'token' => $plainTextToken,
])">
Verify
</x-mail::button>
<x-mail::button :url="route('newsletter.unsubscribe', [
    'id' => $subscriber->id,
    'token' => $plainTextToken,
])">
Unsubscribe
</x-mail::button>
</x-mail::message>
