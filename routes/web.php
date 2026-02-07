<?php

use App\Http\Controllers\PeopleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', [PeopleController::class, 'index'])->name('home');

require __DIR__.'/settings.php';
