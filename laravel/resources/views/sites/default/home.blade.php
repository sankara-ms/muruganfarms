@extends($layout)

@section('content')
  <section class="hero">
    <div class="container hero-grid">
      <div class="hero-copy">
        <h1>{{ __('site.hero.title') }}</h1>
        <p class="hero-text">{{ __('site.hero.text') }}</p>
        <div class="hero-actions">
          <a class="btn btn-primary" href="tel:{{ $site->contact('phone') }}">{{ __('site.actions.call') }}</a>
          <a class="btn btn-outline" href="mailto:{{ $site->contact('email') }}">{{ __('site.contact.email') }}</a>
        </div>
      </div>
    </div>
  </section>
@endsection
