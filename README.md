# Murugan Farms — Website

Production website for Murugan Farms (Tiruvannamalai, Tamil Nadu), built on Laravel 12.
It follows the same multi-client flow as the `cms-bus` project: one app, a client key,
per-client views and assets, shared infrastructure.

## Run locally

Requires WAMP PHP 8.2+ and Composer. From `laravel/`:

```bash
php artisan migrate               # first run only
php artisan serve --host=127.0.0.1 --port=8000
```

Open <http://127.0.0.1:8000>.

## Structure

```
laravel/
├─ config/site.php               # client registry: views, assets, contact, catalogue, gallery
├─ app/Support/Site.php          # resolves the active client + view fallback
├─ app/Support/helpers.php       # site() / site_view()
├─ app/Http/Middleware/SetLocale.php
├─ resources/views/sites/
│  ├─ murugan/                   # template, home and partials for this client
│  └─ default/                   # fallback client when a view is missing
├─ lang/{en,ta}/site.php         # all page copy in English and Tamil
└─ public/
   ├─ css/style.css              # shared design system
   ├─ js/main.js                 # nav, lightbox, enquiry form
   ├─ vendor/                    # jQuery, SAL, Slick
   ├─ assets/images/             # brand, hero, products, gallery
   └─ sites/murugan/             # per-client css + js (motion layer)
```

### Adding another client

1. Add an entry under `sites` in `config/site.php` (name, views, stylesheet, script, contact,
   catalogue, gallery, locales).
2. Create `resources/views/sites/<key>/template.blade.php` and `home.blade.php`
   (copy `murugan/` as a starting point) plus `public/sites/<key>/`.
3. Set `SITE_KEY=<key>` in `laravel/.env` and clear the config cache:
   `php artisan config:clear`.

Missing views fall back to `sites/default`.

## Content

- Copy lives in `laravel/lang/en/site.php` and `laravel/lang/ta/site.php`.
  The current site content (English) is the default locale; Tamil is a full translation.
- Products and gallery items are configured in `config/site.php` (`catalogue`, `gallery`);
  their names, descriptions and captions come from the language files.
- Contact details are configured under `contact` in `config/site.php`:
  - Owners: G. Murugan and M. Santhi
  - Address: Udayanandhal Village, Thenmathur Post, Tiruvannamalai District, Tamil Nadu, India
  - Phone / WhatsApp: `+91 94874 08709`
  - Email: `sankarnaece@gmail.com`

## Behaviour

- **Language:** `/locale/{en|ta}` switches language and stores the choice in the session.
  The header (desktop) and mobile menu both expose the switcher.
- **Animations:** SAL scroll reveals, gentle hero zoom, parallax frames, sticky/hide header,
  scroll progress bar. All motion is disabled under `prefers-reduced-motion`.
- **Mobile:** below 700px the product grid becomes a swipeable Slick carousel with dots;
  a fixed Call / WhatsApp / Enquiry bar is shown.
- **Enquiries:** the form posts JSON to `POST /api/enquiries` and the row is stored in SQLite.

## Tests

```bash
cd laravel
php artisan test
```

The enquiry API is covered by `tests/Feature/EnquiryTest.php`.

## Live site

The public demo site is hosted on GitHub Pages:

**<https://sankara-ms.github.io/muruganfarms/>**

Source and builds live in <https://github.com/sankara-ms/muruganfarms>. English is served
at `/muruganfarms/`, Tamil at `/muruganfarms/ta/index.html`.

```
dist/                     # static export for Qoder Sites (root-relative links)
docs/                     # GitHub Pages build (served from /muruganfarms/)
functions/                # Qoder Edge Function that relays enquiries
scripts/build-pages.mjs   # regenerates docs/ from dist/
```

The original Qoder Sites deployment is still live at
<https://farmingmurugansanthi-22b74f79eo4.qoder.website>. Qoder fixes that account suffix
on every address, which is why the demo uses GitHub Pages.

### Enquiries

There is no database on the static hosts. Both builds send the enquiry to
`sankarnaece@gmail.com`:

- GitHub Pages posts directly to the ShipMyForm relay
  (`https://shipmyform.com/to/sankarnaece@gmail.com`), which allows the browser's
  cross-origin request.
- Qoder posts to `/functions/v1/app`, which validates the form and relays it to the same
  address (the Qoder gateway only accepts same-origin function calls).

Delivery requires a one-time activation: the first relay attempt emails
`sankarnaece@gmail.com` an activation link. Click it once and later enquiries are
forwarded automatically. The WhatsApp and phone buttons work independently.

### Republish

After changing the Laravel app, export the static site to `dist/`, then:

```bash
node scripts/build-pages.mjs   # refresh docs/ for GitHub Pages
git add -A && git commit -m "Update site" && git push
```

Pages rebuilds automatically from `docs/`. For the Qoder copy, prepare and publish a new
action for the existing project (a changed `dist/` needs a new action).

## Backups

The pre-Laravel static site and the older duplicate Laravel project are preserved in the
sibling `../_backups/` directory (`farming-retired-20261104/`, `farming-laravel-retired-20261104/`).
They are not served and can be deleted once no longer needed.
