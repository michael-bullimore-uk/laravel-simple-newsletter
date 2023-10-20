@if (session('newsletter.message'))
    <div class="alert alert-success">
        {{ session('newsletter.message') }}
    </div>
@endif

<form action="{{ route('newsletter.subscribe') }}" method="POST">
    @csrf

    <label for="newsletter-email">{{ __('newsletter::messages.email') }}</label>
    <input
        class="@error('email') is-invalid @enderror"
        id="newsletter-email"
        name="email"
        type="email"
        value="{{ old('email') }}"
    >
    @error('email', 'newsletter'){{-- Named error bag --}}
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
</form>
