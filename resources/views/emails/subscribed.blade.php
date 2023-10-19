{{-- Markdown indentation --}}
<x-mail::message>
Please click the link below to verify your email address.
<x-mail::button :url="$subscriber->getVerifyUrl()" color="success">Verify</x-mail::button>
You can easily opt-out at any time by clicking the unsubscribe link below.
<x-mail::button :url="$subscriber->getUnsubscribeUrl()" color="error">Unsubscribe</x-mail::button>
</x-mail::message>
