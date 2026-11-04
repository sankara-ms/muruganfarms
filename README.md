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
  - Address: Udayanadhal Village, Thenmathur Post, Tiruvannamalai District, Tamil Nadu, India
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

The public site is hosted on Qoder Sites:

**<https://farmingmurugansanthi-22b74f79eo4.qoder.website>**

It is a static export of the Laravel pages, so the published design matches the local app
exactly (English at `/`, Tamil at `/ta/`).

```
dist/                     # exported static site (index.html, ta/index.html, css, js, assets)
functions/index.ts        # Deno entry point for the enquiry endpoint
functions/handler.ts      # validates the form and relays it to the owner's email
```

`dist/` is generated from the running Laravel app, with `http://127.0.0.1:8000` rewritten to
root-relative paths, `/locale/en` → `/`, `/locale/ta` → `/ta/index.html`, and
`/api/enquiries` → `/functions/v1/app`.

### Enquiries

The static site has no database, so the enquiry form posts to the Edge Function, which
validates the payload and forwards it to `sankarnaece@gmail.com`. Delivery uses
[ShipMyForm](https://shipmyform.com) and requires a one-time activation: the first time the
function relays a message, ShipMyForm emails `sankarnaece@gmail.com` an activation link.
Click it once and all later enquiries are forwarded automatically. The WhatsApp and phone
buttons keep working independently of this.

Republishing after a content change:

1. Export the site again from the updated Laravel app into `dist/`.
2. Prepare and publish a new action for the same Qoder Site project (reuse the existing
   `projectId`; a changed `dist/` needs a new action).
3. Run the local tests and re-check the live form once after publishing.

## Backups

The pre-Laravel static site and the older duplicate Laravel project are preserved in the
sibling `../_backups/` directory (`farming-retired-20261104/`, `farming-laravel-retired-20261104/`).
They are not served and can be deleted once no longer needed.
