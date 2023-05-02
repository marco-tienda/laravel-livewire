<?php

use App\Http\Livewire\ArticleForm;
use App\Http\Livewire\ArticleShow;
use App\Http\Livewire\ArticlesTable;
use Illuminate\Support\Facades\Route;

Route::get('/blog/{article}', ArticleShow::class)
    ->name('articles.show');

Route::middleware(['auth:sanctum', 'verified'])->prefix('dashboard')->group(function() {
    Route::view('/', 'dashboard')->name('dashboard');

    Route::get('/blog', ArticlesTable::class)
        ->name('articles.index');

    Route::get('/blog/crear', ArticleForm::class)
        ->name('articles.create');

    Route::get('/blog/{article:id}/edit', ArticleForm::class)
        ->name('articles.edit');
});
