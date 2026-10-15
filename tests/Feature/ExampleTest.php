<?php

it('loads the public pages', function (string $route) {
    $this->get(route($route))->assertOk();
})->with([
    'public.home',
    'public.about',
    'public.services',
    'public.doctors',
    'public.contact',
    'login',
    'register',
    'register.doctor',
]);
