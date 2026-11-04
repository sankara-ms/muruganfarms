<?php

namespace App\Support;

use Illuminate\Support\Facades\View;

/**
 * Resolves the active client ("site") from config/site.php.
 *
 * Mirrors the cms-bus flow: the environment names the client, the registry
 * supplies its branding/catalogue, and views resolve per client first with
 * a shared `default` fallback.
 */
class Site
{
    protected array $registry;

    protected string $key;

    protected array $raw;

    protected array $base;

    public function __construct()
    {
        $this->registry = (array) config('site.sites', []);

        $fallback = (string) config('site.fallback', 'default');
        $requested = (string) config('site.active', $fallback);

        $this->key = array_key_exists($requested, $this->registry) ? $requested : $fallback;
        $this->raw = (array) ($this->registry[$this->key] ?? []);
        $this->base = (array) ($this->registry[$fallback] ?? []);
    }

    public function key(): string
    {
        return $this->key;
    }

    public function config(?string $name = null, mixed $default = null): mixed
    {
        if ($name === null) {
            return array_merge($this->base, $this->raw);
        }

        return $this->raw[$name] ?? $this->base[$name] ?? $default;
    }

    public function contact(string $name, mixed $default = null): mixed
    {
        return data_get($this->config('contact', []), $name, $default);
    }

    public function locales(): array
    {
        $locales = (array) $this->config('locales', ['en']);

        return $locales === [] ? ['en'] : $locales;
    }

    /**
     * Per-client view with a fallback to the shared default client.
     */
    public function viewName(string $name): string
    {
        foreach ([$this->key, (string) config('site.fallback', 'default')] as $client) {
            $candidate = "sites.{$client}.{$name}";
            if (View::exists($candidate)) {
                return $candidate;
            }
        }

        abort(500, "Missing view [{$name}] for site [{$this->key}].");
    }

    public function stylesheet(): ?string
    {
        $path = $this->config('stylesheet');

        return $path ? asset($path) : null;
    }

    public function script(): ?string
    {
        $path = $this->config('script');

        return $path ? asset($path) : null;
    }

    public function themeColor(): string
    {
        return (string) $this->config('theme_color', '#17573a');
    }
}
