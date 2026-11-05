@php
  $locale = app()->getLocale();
  $phone = $site->contact('phone');
  $phoneDisplay = $site->contact('phone_display');
@endphp
<header class="site-header" id="top">
  <div class="container header-inner">
    <a class="brand" href="#home" aria-label="{{ __('site.a11y.back_to_top') }}">
      <img class="brand-mark" src="{{ asset('assets/images/logo.png') }}?v=20261105e" alt="" width="46" height="46" />
      <span class="brand-copy">
        <strong class="brand-name">{{ __('site.brand.name') }}</strong>
        <small class="brand-tag">{{ __('site.brand.tagline') }}</small>
      </span>
    </a>

    <nav class="main-nav" id="mainNav" aria-label="{{ __('site.nav.label') }}">
      <a href="#home" class="nav-link active">{{ __('site.nav.home') }}</a>
      <a href="#products" class="nav-link">{{ __('site.nav.products') }}</a>
      <a href="#about" class="nav-link">{{ __('site.nav.about') }}</a>
      <a href="#gallery" class="nav-link">{{ __('site.nav.gallery') }}</a>
      <a href="#contact" class="nav-link">{{ __('site.nav.contact') }}</a>

      <div class="lang-switch lang-switch-mobile" role="group" aria-label="{{ __('site.language.label') }}">
        @foreach ($site->locales() as $option)
          <a
            class="lang-option {{ $locale === $option ? 'is-active' : '' }}"
            href="{{ route('locale.switch', ['locale' => $option]) }}"
            hreflang="{{ $option }}"
            lang="{{ $option }}"
            @if ($locale === $option) aria-current="true" @endif
          >{{ __('site.language.short.' . $option) }}</a>
        @endforeach
      </div>
    </nav>

    <div class="header-actions">
      <div class="lang-switch" role="group" aria-label="{{ __('site.language.label') }}">
        @foreach ($site->locales() as $option)
          <a
            class="lang-option {{ $locale === $option ? 'is-active' : '' }}"
            href="{{ route('locale.switch', ['locale' => $option]) }}"
            hreflang="{{ $option }}"
            lang="{{ $option }}"
            title="{{ __('site.language.' . $option) }}"
            @if ($locale === $option) aria-current="true" @endif
          >{{ __('site.language.short.' . $option) }}</a>
        @endforeach
      </div>

      <a class="btn btn-primary btn-small" href="tel:{{ $phone }}">
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/>
        </svg>
        {{ __('site.actions.call') }}
      </a>

      <button
        class="menu-toggle"
        type="button"
        aria-expanded="false"
        aria-controls="mainNav"
        aria-label="{{ __('site.actions.menu_open') }}"
      >
        <span class="menu-bar"></span>
        <span class="menu-bar"></span>
        <span class="menu-bar"></span>
      </button>
    </div>
  </div>
</header>
