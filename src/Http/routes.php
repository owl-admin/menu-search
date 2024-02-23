<?php

use Slowlyo\OwlMenuSearch\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('menu-search', [Controllers\OwlMenuSearchController::class, 'index']);
