<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', $site->config('name', 'Murugan Farms'))</title>
    <meta name="theme-color" content="{{ $site->themeColor() }}" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  </head>
  <body>
    <header class="site-header">
      <div class="container header-inner">
        <a class="brand" href="{{ route('home') }}">
          <img class="brand-mark" src="{{ asset('assets/images/brand-mark.svg') }}" alt="" width="46" height="46" />
          <span class="brand-copy">
            <strong class="brand-name">{{ $site->config('name', 'Murugan Farms') }}</strong>
            <small class="brand-tag">{{ __('site.brand.tagline') }}</small>
          </span>
        </a>
      </div>
    </header>

    <main id="main">
      @yield('content')
    </main>

    <footer class="site-footer">
      <div class="container footer-bottom-inner">
        <p>© <span id="year"></span> {{ $site->config('name', 'Murugan Farms') }}</p>
      </div>
    </footer>
    <script>
      document.getElementById('year').textContent = new Date().getFullYear();
    </script>
  </body>
</html>
