@extends($layout)

@section('content')
  <!-- ===================== Hero ===================== -->
  <section class="hero" id="home">
    <div class="container hero-grid">
      <div class="hero-copy" data-sal="slide-right" data-sal-duration="700" data-sal-easing="ease-out-cubic">
        <p class="location-pill">
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/>
            <circle cx="12" cy="10" r="3"/>
          </svg>
          {{ __('site.hero.location') }}
        </p>
        <h1>{{ __('site.hero.title') }}</h1>
        <p class="hero-text">{{ __('site.hero.text') }}</p>
        <div class="hero-actions">
          <a href="#products" class="btn btn-primary btn-magnetic">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/>
              <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
            </svg>
            {{ __('site.actions.explore') }}
          </a>
          <a href="#contact" class="btn btn-outline btn-magnetic">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
            {{ __('site.actions.contact_farmer') }}
          </a>
        </div>
        <p class="hero-note">{{ __('site.hero.note') }}</p>
      </div>

      <div class="hero-visual" data-sal="zoom-in" data-sal-duration="900" data-sal-delay="120" data-sal-easing="ease-out-back">
        <div class="hero-frame" data-parallax="0.06">
          <img
            class="ken-burns"
            src="{{ asset('assets/images/hero-farmer.jpg') }}?v=20261026c"
            alt="{{ __('site.hero.image_alt') }}"
            width="1536"
            height="1024"
            fetchpriority="high"
            onerror="this.onerror=null;this.src='{{ asset('assets/images/placeholder.svg') }}';"
          />
        </div>
        <p class="hero-stamp" aria-hidden="true">{{ __('site.hero.stamp_line_1') }}<br />{{ __('site.hero.stamp_line_2') }}</p>
        <svg class="hero-leaf hero-leaf-one" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/>
          <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
        </svg>
        <svg class="hero-leaf hero-leaf-two" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/>
        </svg>
      </div>
    </div>
  </section>

  <!-- ===================== Products ===================== -->
  <section class="products" id="products">
    <div class="container">
      <div class="section-head" data-sal="slide-up" data-sal-duration="600">
        <div class="section-head-main">
          <svg class="leaf-badge" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/>
            <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
          </svg>
          <div>
            <h2>{{ __('site.products.title') }}</h2>
            <p class="section-sub">{{ __('site.products.sub') }}</p>
          </div>
        </div>
        <p class="section-side">{{ __('site.products.side') }}</p>
      </div>

      <div class="product-grid" id="productGrid" data-slick-mobile>
        @foreach ($catalogue as $index => $item)
          @php $copy = __('site.products.items.' . $item['key']); @endphp
          <article class="product-card" data-sal="slide-up" data-sal-delay="{{ ($index % 3) * 90 }}" data-sal-duration="550">
            <img
              src="{{ asset($item['image']) }}"
              alt="{{ $copy['alt'] }}"
              loading="lazy"
              onerror="this.onerror=null;this.src='{{ asset('assets/images/placeholder.svg') }}';"
            />
            <div class="product-body">
              <h3>{{ $copy['name'] }}</h3>
              <p>{{ $copy['description'] }}</p>
              <div class="product-meta">
                <span class="product-price">{{ __('site.products.price') }}</span>
                <button type="button" class="enquire-btn" data-product="{{ $item['key'] }}">
                  {{ __('site.actions.enquire') }}
                  <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 12h14"/><path d="M12 5l7 7-7 7"/>
                  </svg>
                </button>
              </div>
            </div>
          </article>
        @endforeach
      </div>

      <p class="inventory-note" data-sal="fade">{{ __('site.products.note') }}</p>
    </div>
  </section>

  <!-- ===================== Value strip ===================== -->
  <section class="values" aria-label="{{ __('site.values.label') }}">
    <div class="container values-grid">
      @php
        $valueIcons = [
          '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>',
          '<path d="M7 20h10"/><path d="M10 20c5.5-2.5.8-6.4 3-10"/><path d="M9.5 9.4c1.1.8 1.8 2.2 2.3 3.7-2 .4-3.5.4-4.8-.3-1.2-.6-2.3-1.9-3-4.2 2.8-.5 4.4 0 5.5.8z"/><path d="M14.1 6a7 7 0 0 0-1.1 4c1.9-.1 3.3-.6 4.3-1.4 1-1 1.6-2.3 1.7-4.6-3.1.1-4.3.9-4.9 2z"/>',
          '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/><path d="M3.22 12H9.5l.5-1 2 4.5 2-7 1.5 3.5h5.27"/>',
          '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        ];
      @endphp
      @foreach (__('site.values.items') as $index => $value)
        <div class="value-item" data-sal="slide-up" data-sal-delay="{{ $index * 100 }}" data-sal-duration="550">
          <span class="value-icon">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              {!! $valueIcons[$index] ?? $valueIcons[0] !!}
            </svg>
          </span>
          <div>
            <h3>{{ $value['title'] }}</h3>
            <p>{{ $value['text'] }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </section>

  <!-- ===================== About ===================== -->
  <section class="about" id="about">
    <div class="container about-grid">
      <div class="about-visual" data-sal="slide-right" data-sal-duration="700">
        <div class="about-frame" data-parallax="-0.04">
          <img
            src="{{ asset('assets/images/farmer-portrait.jpg') }}?v=20261026c"
            alt="{{ __('site.about.image_alt') }}"
            width="1024"
            height="1536"
            loading="lazy"
            onerror="this.onerror=null;this.src='{{ asset('assets/images/placeholder.svg') }}';"
          />
        </div>
        <div class="about-badge">
          <strong>{{ __('site.about.badge_title') }}</strong>
          <span>{{ __('site.about.badge_text') }}</span>
        </div>
      </div>

      <div class="about-copy" data-sal="slide-left" data-sal-duration="700" data-sal-delay="80">
        <p class="section-kicker">{{ __('site.about.kicker') }}</p>
        <h2>{{ __('site.about.title') }}</h2>
        <p>{{ __('site.about.text') }}</p>
        <ul class="about-points">
          @foreach (__('site.about.points') as $point)
            <li>{{ $point }}</li>
          @endforeach
        </ul>
        <p class="signature" aria-hidden="true">{{ __('site.about.signature') }}</p>
        <p class="signature-meta">
          <strong>{{ __('site.about.signature_meta_name') }}</strong><br />{{ __('site.about.signature_meta') }}
        </p>
        <a href="#contact" class="btn btn-primary btn-magnetic">
          {{ __('site.actions.learn_more') }}
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M5 12h14"/>
            <path d="M12 5l7 7-7 7"/>
          </svg>
        </a>
      </div>
    </div>
  </section>

  <!-- ===================== Gallery ===================== -->
  <section class="gallery" id="gallery">
    <div class="container">
      <div class="section-head" data-sal="slide-up" data-sal-duration="600">
        <div class="section-head-main">
          <svg class="leaf-badge" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/>
            <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
          </svg>
          <div>
            <h2>{{ __('site.gallery.title') }}</h2>
            <p class="section-sub">{{ __('site.gallery.sub') }}</p>
          </div>
        </div>
        <p class="section-side gallery-hint">{{ __('site.gallery.hint') }}</p>
      </div>

      <div class="gallery-grid" id="galleryGrid">
        @foreach ($gallery as $index => $shot)
          @php $copy = __('site.gallery.items.' . $shot['key']); @endphp
          <figure class="gallery-item" data-sal="zoom-in" data-sal-delay="{{ ($index % 3) * 70 }}" data-sal-duration="550">
            <button type="button" data-index="{{ $index }}" aria-label="{{ __('site.gallery.view_larger') }}: {{ $copy['caption'] }}">
              <img
                src="{{ asset($shot['image']) }}"
                alt="{{ $copy['alt'] }}"
                loading="lazy"
                onerror="this.onerror=null;this.src='{{ asset('assets/images/placeholder.svg') }}';"
              />
            </button>
            <figcaption>{{ $copy['caption'] }}</figcaption>
          </figure>
        @endforeach
      </div>
    </div>
  </section>

  <!-- ===================== Contact ===================== -->
  <section class="contact" id="contact">
    <div class="container">
      <div class="contact-card" data-sal="slide-up" data-sal-duration="700">
        <div class="contact-info">
          <p class="section-kicker on-dark">{{ __('site.contact.kicker') }}</p>
          <h2>{{ __('site.contact.title') }}</h2>
          <p class="contact-intro">{{ __('site.contact.intro') }}</p>

          <ul class="contact-list">
            <li class="contact-row">
              <span class="contact-icon" aria-hidden="true">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>
              </span>
              <div>
                <span class="contact-label">{{ __('site.contact.location') }}</span>
                <p>{{ __('site.contact.address') }}</p>
              </div>
            </li>
            <li class="contact-row">
              <span class="contact-icon" aria-hidden="true">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
              </span>
              <div>
                <span class="contact-label">{{ __('site.contact.phone') }}</span>
                <p><a href="tel:{{ $site->contact('phone') }}">{{ $site->contact('phone_display') }}</a></p>
              </div>
            </li>
            <li class="contact-row">
              <span class="contact-icon" aria-hidden="true">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
              </span>
              <div>
                <span class="contact-label">{{ __('site.contact.email') }}</span>
                <p><a href="mailto:{{ $site->contact('email') }}">{{ $site->contact('email') }}</a></p>
              </div>
            </li>
          </ul>

          <a class="btn btn-gold btn-magnetic" href="tel:{{ $site->contact('phone') }}">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
            {{ __('site.actions.call') }}
          </a>
        </div>

        <div class="contact-form-wrap">
          <form class="enquiry-form" id="enquiryForm" novalidate>
            <h3>
              <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/>
                <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
              </svg>
              {{ __('site.actions.send_enquiry_title') }}
            </h3>

            <div class="field">
              <label for="name">{{ __('site.form.name') }} <span class="req" aria-hidden="true">*</span></label>
              <input id="name" name="name" type="text" autocomplete="name" placeholder="{{ __('site.form.name_placeholder') }}" aria-describedby="nameError" />
              <small class="field-error" id="nameError" aria-live="polite"></small>
            </div>

            <div class="field">
              <label for="phone">{{ __('site.form.phone') }} <span class="req" aria-hidden="true">*</span></label>
              <input id="phone" name="phone" type="tel" inputmode="tel" autocomplete="tel" placeholder="{{ __('site.form.phone_placeholder') }}" aria-describedby="phoneError" />
              <small class="field-error" id="phoneError" aria-live="polite"></small>
            </div>

            <div class="field">
              <label for="product">{{ __('site.form.product') }}</label>
              <select id="product" name="product">
                <option value="">{{ __('site.form.product_general') }}</option>
                @foreach ($catalogue as $item)
                  <option value="{{ $item['key'] }}">{{ __('site.products.items.' . $item['key'] . '.name') }}</option>
                @endforeach
                @foreach ($enquiryExtras as $extra)
                  <option value="{{ $extra }}">{{ __('site.products.extras.' . $extra) }}</option>
                @endforeach
              </select>
            </div>

            <div class="field">
              <label for="message">{{ __('site.form.message') }} <span class="req" aria-hidden="true">*</span></label>
              <textarea id="message" name="message" rows="4" placeholder="{{ __('site.form.message_placeholder') }}" aria-describedby="messageError"></textarea>
              <small class="field-error" id="messageError" aria-live="polite"></small>
            </div>

            <button class="btn btn-primary btn-block" type="submit">
              {{ __('site.actions.send_enquiry') }}
              <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M5 12h14"/>
                <path d="M12 5l7 7-7 7"/>
              </svg>
            </button>
            <p class="form-success" id="formSuccess" role="status" aria-live="polite" hidden></p>
          </form>
        </div>
      </div>
    </div>
  </section>
@endsection
