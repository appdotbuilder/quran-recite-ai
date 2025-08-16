<?php

declare(strict_types=1);

test('controllers have standard methods')
    ->expect('App\Http\Controllers')
    ->not->toHavePublicMethodsBesides([
        '__construct',
        '__invoke',
        'index',
        'show',
        'create',
        'store',
        'edit',
        'update',
        'destroy',
        'middleware',
    ])
    ->ignoring([
        'App\Http\Controllers\Auth',
        'App\Http\Controllers\Controller',
        'App\Http\Controllers\Settings',
    ]);

test('models extend eloquent')
    ->expect('App\Models')
    ->toExtend('Illuminate\Database\Eloquent\Model')
    ->ignoring('App\Models\User');

test('no debug functions')
    ->expect(['dd', 'dump', 'var_dump', 'print_r', 'die', 'exit', 'ray', 'rand'])
    ->not->toBeUsed();