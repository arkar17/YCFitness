<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
        'ewallet_store',
        'ewallet_store/*',

        'bank_payment_store',
        'bank_payment_store/*',

        '/data/save',
        '/logout',
        'customer/customerCreate',
        '/trainer/group/create',
        'customer/training_center/workout/workout_complete/store/',

    ];
}
