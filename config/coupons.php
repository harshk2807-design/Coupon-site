<?php

return [
    /*
    | Your main domain WITHOUT "www." and without "https://".
    |   local (Herd):   coupon-site.test
    |   live:           yourdomain.com
    */
    'domain' => env('APP_DOMAIN', 'localhost'),

    /*
    | true  -> store pages at https://temu.yourdomain.com/coupon/
    |          (needs a wildcard *.yourdomain.com DNS record + subdomain on hosting)
    | false -> store pages at https://yourdomain.com/store/temu/
    |          (use this on temporary domains)
    */
    'use_subdomains' => (bool) env('USE_SUBDOMAINS', false),
];
