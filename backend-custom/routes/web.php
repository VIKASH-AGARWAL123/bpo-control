<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'name' => 'BPO Control API',
        'status' => 'ok',
    ]);
});
