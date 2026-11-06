<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <script>
      // Static builds only (the export tags <html data-static-locale>): the device's
      // own language picks the page — Tamil anywhere in the list means Tamil, because
      // Chrome can lead with en-IN on a Tamil phone. A manual switch is remembered,
      // but only while the phone still speaks the language it was made in.
      (function () {
        var root = document.documentElement;
        if (!root.hasAttribute("data-static-locale")) return;

        var here = (root.getAttribute("lang") || "en").toLowerCase();
        var hereIsTamil = here.indexOf("ta") === 0;

        function store(key, value) {
          try { localStorage.setItem(key, value); } catch (err) {}
        }
        function read(key) {
          try { return localStorage.getItem(key) || ""; } catch (err) { return ""; }
        }
        function go(toTamil) {
          // Kept free of backslash-escaped slashes: the static export unescapes them
          // mid-file and would leave an invalid pattern behind.
          var dir = location.href.split("#")[0].split("?")[0].replace(/[^/]*$/, "");
          var inTaDir = dir.slice(-3) === "ta/";
          if (toTamil) {
            // Some hosts answer /ta/ with the English page, so a Tamil visitor
            // already inside the Tamil directory needs the file, not a second
            // "ta/" segment that leads to a dead path.
            location.replace(inTaDir ? dir + "index.html" : dir + "ta/index.html");
            return;
          }
          // Only the Tamil directory has a known spelling, so recognise that and
          // nothing else — a guess here would send the visitor to a dead path.
          if (!inTaDir) return;
          location.replace(dir.slice(0, -3) + "index.html");
        }

        var prefs = [];
        try {
          prefs = (navigator.languages && navigator.languages.length ? navigator.languages : [navigator.language || "en"])
            .map(function (tag) { return String(tag).toLowerCase(); });
        } catch (err) { prefs = ["en"]; }

        var first = prefs[0];
        var deviceIsTamil = prefs.some(function (tag) { return /^ta([-_]|$)/.test(tag); });

        // A shared link can name the language outright: ?lang=ta / ?lang=en.
        // Remembering it against today's device language is what stops the next page
        // from re-running detection and bouncing straight back.
        var forced = /[?&]lang=(ta|en)\b/i.exec(location.search);
        if (forced) {
          var want = forced[1].toLowerCase();
          store("mf-locale", want);
          store("mf-locale-device", first);
          if ((want === "ta") !== hereIsTamil) go(want === "ta");
          return;
        }

        // Respect a manual switch, but only while the phone still speaks the language
        // it was made in: changing the device language is itself an instruction.
        var picked = read("mf-locale");
        if (picked && read("mf-locale-device") === first) return;

        store("mf-locale-device", first);
        if (deviceIsTamil !== hereIsTamil) go(deviceIsTamil);
      })();
    </script>
    <title>@yield('title', __('site.meta.title'))</title>
    <meta name="description" content="@yield('description', __('site.meta.description'))" />
    <meta name="theme-color" content="{{ $site->themeColor() }}" />
    <script>
      document.documentElement.classList.add("js");
    </script>
@php
    // Crawlers need one absolute URL per page, so the two static builds rewrite
    // these root-relative values to their own host (see scripts/*.mjs).
    $shareLocale = str_replace('_', '-', app()->getLocale());
    $sharePath = $shareLocale === 'ta' ? '/ta/' : '/';
    $shareImage = asset('assets/images/share-card.jpg');
@endphp
    <link rel="manifest" href="{{ asset('manifest.json') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="{{ $site->config('name') }}" />
    <meta property="og:locale" content="{{ $shareLocale === 'ta' ? 'ta_IN' : 'en_US' }}" />
    <meta property="og:url" content="{{ $sharePath }}" />
    <meta property="og:title" content="{{ __('site.meta.og_title') }}" />
    <meta property="og:description" content="{{ __('site.meta.og_description') }}" />
    <meta property="og:image" content="{{ $shareImage }}" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt" content="{{ __('site.meta.og_image_alt') }}" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ __('site.meta.og_title') }}" />
    <meta name="twitter:description" content="{{ __('site.meta.og_description') }}" />
    <meta name="twitter:image" content="{{ $shareImage }}" />
    <meta name="twitter:image:alt" content="{{ __('site.meta.og_image_alt') }}" />
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}?v=20261106b" />
    <link rel="apple-touch-icon" href="{{ asset('assets/images/logo.png') }}?v=20261106b" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=Kalam:wght@400;700&family=Noto+Sans+Tamil:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link rel="preload" as="image" href="{{ asset('assets/images/hero-farmer.jpg') }}?v=20261026c" />
    <link rel="stylesheet" href="{{ asset('vendor/sal/sal.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/slick/slick.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v=20261106b" />
    @if ($site->stylesheet())
      <link rel="stylesheet" href="{{ $site->stylesheet() }}?v=20261106b" />
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
        visitUrl: @json(route('api.visits.store')),
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
    <script src="{{ asset('js/main.js') }}?v=20261106b"></script>
    @if ($site->script())
      <script src="{{ $site->script() }}?v=20261106b"></script>
    @endif
  </body>
</html>
