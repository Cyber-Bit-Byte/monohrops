<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    require __DIR__ . '/api/v1.php';
});
