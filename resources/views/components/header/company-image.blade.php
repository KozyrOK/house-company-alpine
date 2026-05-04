@php
    $logo = asset('images/default-image-company.webp');

    if (auth()->check() && !auth()->user()->isSuperAdmin() && currentCompany()) {
        $logo = currentCompany()->logo_url;
    }
@endphp

<div class="company-image-header-container">
    @auth
        <a href="{{ url('/') }}">
            <img
                src="{{ $logo }}"
                alt="Company Logo"
                class="company-image-header"
                loading="lazy">
        </a>
    @else
        <img
            src="{{ $logo }}"
            alt="Company Logo"
            class="company-image-header"
            loading="lazy">
    @endauth
</div>
