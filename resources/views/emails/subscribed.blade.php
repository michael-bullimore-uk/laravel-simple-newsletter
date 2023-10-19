{{-- Markdown indentation --}}
<x-mail::message>
# Verify
{{ $subscriber->email }}
<x-mail::button :url="$subscriber->getVerifyUrl()">
Verify
</x-mail::button>
<x-mail::button :url="$subscriber->getUnsubscribeUrl()">
Unsubscribe
</x-mail::button>
</x-mail::message>
