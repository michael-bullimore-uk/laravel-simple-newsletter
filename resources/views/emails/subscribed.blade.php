{{-- Markdown indentation --}}
<x-mail::message>
{{ __('newsletter::messages.mail.verify_text') }}
<x-mail::button :url="$subscriber->getVerifyUrl()" color="success">{{ __('newsletter::messages.mail.verify_button') }}</x-mail::button>
{{ __('newsletter::messages.mail.unsubscribe_text') }}
<x-mail::button :url="$subscriber->getUnsubscribeUrl()" color="error">{{ __('newsletter::messages.mail.unsubscribe_button') }}</x-mail::button>
</x-mail::message>
