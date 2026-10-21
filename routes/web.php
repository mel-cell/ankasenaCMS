<?php

use Illuminate\Support\Facades\Route;

// Halaman pratinjau desain — hanya di lokal, bukan halaman publik.
if (app()->isLocal()) {
    Route::view('/design', 'design')->name('design.preview');
}

// Route::statamic('example', 'example-view', [
//    'title' => 'Example'
// ]);
