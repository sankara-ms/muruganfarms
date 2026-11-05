@php
  $phone = $site->contact('phone');
  $phoneDisplay = $site->contact('phone_display');
  $email = $site->contact('email');
  $whatsapp = $site->contact('whatsapp');
@endphp
<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-brand">
      <a class="brand" href="#home" aria-label="{{ __('site.a11y.back_to_top') }}">
        <img class="brand-mark" src="{{ asset('assets/images/logo.png') }}?v=20261105a" alt="" width="42" height="42" />
        <span class="brand-copy">
          <strong class="brand-name">{{ __('site.brand.name') }}</strong>
          <small class="brand-tag">{{ __('site.brand.tagline') }}</small>
        </span>
      </a>
      <p class="footer-blurb">{{ __('site.footer.blurb') }}</p>
    </div>

    <nav class="footer-links" aria-label="{{ __('site.footer.nav_label') }}">
      <h3>{{ __('site.footer.quick_links') }}</h3>
      <ul>
        <li><a href="#home">{{ __('site.nav.home') }}</a></li>
        <li><a href="#products">{{ __('site.nav.products') }}</a></li>
        <li><a href="#about">{{ __('site.nav.about') }}</a></li>
        <li><a href="#gallery">{{ __('site.nav.gallery') }}</a></li>
        <li><a href="#contact">{{ __('site.nav.contact') }}</a></li>
      </ul>
    </nav>

    <div class="footer-social">
      <h3>{{ __('site.footer.follow') }}</h3>
      <ul class="social-list">
        <li>
          <a href="{{ $whatsapp }}" target="_blank" rel="noreferrer" aria-label="WhatsApp">
            <svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M17.47 14.38c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.96-.94 1.16-.17.2-.35.22-.64.08-.3-.15-1.26-.47-2.4-1.48-.88-.79-1.48-1.76-1.65-2.06-.17-.3-.02-.46.13-.6.13-.14.3-.35.44-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.61-.92-2.2-.24-.58-.49-.5-.67-.51-.17-.01-.37-.01-.57-.01s-.52.07-.79.37c-.27.3-1.04 1.02-1.04 2.48s1.06 2.87 1.21 3.07c.15.2 2.1 3.2 5.08 4.49.71.3 1.26.49 1.7.62.71.23 1.36.2 1.87.12.57-.08 1.76-.72 2-1.41.25-.7.25-1.29.18-1.42-.08-.12-.28-.2-.57-.34m-5.42 7.4h-.01a9.87 9.87 0 0 1-5.03-1.38l-.36-.21-3.74.98 1-3.65-.24-.37a9.86 9.86 0 0 1-1.51-5.26c0-5.45 4.44-9.88 9.89-9.88a9.83 9.83 0 0 1 6.99 2.9 9.83 9.83 0 0 1 2.89 6.99c0 5.45-4.44 9.88-9.88 9.88m8.41-18.3A11.82 11.82 0 0 0 12.05 0C5.5 0 .16 5.34.16 11.89c0 2.1.55 4.14 1.59 5.95L.06 24l6.3-1.65a11.9 11.9 0 0 0 5.68 1.45h.01c6.55 0 11.89-5.34 11.89-11.89 0-3.18-1.24-6.16-3.48-8.41z"/>
            </svg>
          </a>
        </li>
        <li>
          <a href="tel:{{ $phone }}" aria-label="{{ __('site.actions.call') }}">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
          </a>
        </li>
        <li>
          <a href="mailto:{{ $email }}" aria-label="{{ __('site.contact.email') }}">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
            </svg>
          </a>
        </li>
      </ul>
    </div>

    <div class="footer-emblem" data-sal="fade" aria-hidden="true">
      <img src="{{ asset('assets/images/footer-emblem.png') }}?v=20261105a" alt="" width="240" height="240" loading="lazy" />
    </div>
  </div>

  <div class="footer-bottom">
    <div class="container footer-bottom-inner">
      <p>© <span id="year"></span> {{ __('site.brand.name') }} · Tiruvannamalai, Tamil Nadu · TN-25</p>
      <p class="powered">
        {{ __('site.footer.powered') }}
        <a href="https://sankara-profile.onrender.com/" target="_blank" rel="noreferrer">Sankar MS</a>
        · {{ __('site.footer.want_site') }}
        <a href="https://sankara-profile.onrender.com/" target="_blank" rel="noreferrer">{{ __('site.footer.contact_me') }}</a>
      </p>
    </div>
  </div>
</footer>
