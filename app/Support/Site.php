<?php

namespace App\Support;

/**
 * Builds links to the MAIN site.
 * Needed because on a store subdomain (temu.yourdomain.com) a normal url('/')
 * would point to the subdomain, but menus must go back to yourdomain.com.
 */
class Site
{
    /** https://yourdomain.com + $path */
    public static function url(string $path = '/'): string
    {
        return self::scheme() . '://' . config('coupons.domain') . self::port() . '/' . ltrim($path, '/');
    }

    /** https://temu.yourdomain.com/coupon  OR  https://yourdomain.com/store/temu */
    public static function storeUrl(string $slug): string
    {
        if (config('coupons.use_subdomains')) {
            return self::scheme() . '://' . $slug . '.' . config('coupons.domain') . self::port() . '/coupon';
        }

        return self::url("store/{$slug}");
    }

    public static function categoryUrl(string $slug): string
    {
        return self::url("category/{$slug}");
    }

    private static function scheme(): string
    {
        return parse_url(config('app.url'), PHP_URL_SCHEME) ?: 'https';
    }

    private static function port(): string
    {
        $port = parse_url(config('app.url'), PHP_URL_PORT);

        return $port ? ':' . $port : '';
    }
}
