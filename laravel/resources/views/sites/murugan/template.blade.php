<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <script>
      // Static builds only (the export tags <html data-static-locale>): the device's
      // own language picks the page — Tamil first means Tamil, anything else English.
      // A manual switch is remembered, so this never overwrites a real choice.
      (function () {
        var root = document.documentElement;
        if (!root.hasAttribute("data-static-locale")) return;
        var stored = "";
        try { stored = localStorage.getItem("mf-locale") || ""; } catch (err) {}
        if (stored) return;
        var first = String((navigator.languages && navigator.languages[0]) || navigator.language || "en").toLowerCase();
        var tamil = /^(ta)([-_]|$)/.test(first);
        var here = (root.getAttribute("lang") || "en").toLowerCase();
        if (tamil && here.indexOf("ta") !== 0) {
          location.replace(new URL("ta/index.html", location.href).href);
        } else if (!tamil && here.indexOf("ta") === 0) {
          location.replace(new URL("../index.html", location.href).href);
        }
      })();
    </script>
    <title>@yield('title', __('site.meta.title'))</title>
    <meta name="description" content="@yield('description', __('site.meta.description'))" />
    <meta name="theme-color" content="{{ $site->themeColor() }}" />
    <script>
      document.documentElement.classList.add("js");
    </script>
    <meta property="og:title" content="{{ __('site.meta.og_title') }}" />
    <meta property="og:description" content="{{ __('site.meta.og_description') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="{{ asset('assets/images/hero-farmer.jpg') }}" />
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}?v=20261105e" />
    <link rel="apple-touch-icon" href="{{ asset('assets/images/logo.png') }}?v=20261105e" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=Kalam:wght@400;700&family=Noto+Sans+Tamil:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link rel="preload" as="image" href="{{ asset('assets/images/hero-farmer.jpg') }}?v=20261026c" />
    <link rel="stylesheet" href="{{ asset('vendor/sal/sal.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/slick/slick.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v=20261105e" />
    @if ($site->stylesheet())
      <link rel="stylesheet" href="{{ $site->stylesheet() }}?v=20261105e" />
    @endif
  </head>
  <body class="site-{{ $site->key() }}">
    <a class="skip-link" href="#main">{{ __('site.a11y.skip') }}</a>
    <div class="scroll-progress" aria-hidden="true"><span id="scrollProgressBar"></span></div>

    @include('sites.murugan.partials.header')

    <main id="main">
      @yield('content')
    </main>

    @include('sites.murugan.partials.footer')
    @include('sites.murugan.partials.quickbar')
    @include('sites.murugan.partials.lightbox')

    <button class="to-top" id="toTop" type="button" aria-label="{{ __('site.a11y.back_to_top') }}">
      <svg class="to-top-ring" viewBox="0 0 44 44" aria-hidden="true">
        <circle class="to-top-track" cx="22" cy="22" r="20" />
        <circle class="to-top-bar" id="toTopBar" cx="22" cy="22" r="20" />
      </svg>
      <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M12 19V5" /><path d="m5 12 7-7 7 7" />
      </svg>
    </button>

    <script>
      window.SITE = {
        locale: @json(app()->getLocale()),
        enquiryUrl: @json(route('api.enquiries.store')),
        csrf: @json(csrf_token()),
        strings: {
          errors: @json(__('site.form.errors')),
          sending: @json(__('site.form.sending')),
          success: @json(__('site.form.success')),
          error: @json(__('site.form.error')),
          viewLarger: @json(__('site.gallery.view_larger')),
          menuOpen: @json(__('site.actions.menu_open')),
          menuClose: @json(__('site.actions.menu_close')),
        },
      };
    </script>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/slick/slick.min.js') }}"></script>
    <script src="{{ asset('vendor/sal/sal.js') }}"></script>
    <script src="{{ asset('js/main.js') }}?v=20261105e"></script>
    @if ($site->script())
      <script src="{{ $site->script() }}?v=20261105e"></script>
    @endif
  </body>
</html>
