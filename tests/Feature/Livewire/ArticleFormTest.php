<?php

namespace Tests\Feature\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ArticleFormTest extends TestCase
{
    use RefreshDatabase;

    function test_can_create_new_articles()
    {
        Livewire::test('article-form')
            ->set('article.title', 'New article')
            ->set('article.content', 'Article content')
            ->call('save')
            ->assertSessionHas('status')
            ->assertRedirect(route('articles.index'))
        ;

        $this->assertDatabaseHas('articles', [
            'title' => 'New article',
            'content' => 'Article content'
        ]);
    }

    function test_title_is_required()
    {
        Livewire::test('article-form')
            ->set('article.content', 'Article content')
            ->call('save')
            ->assertHasErrors(['article.title' => 'required']) # Hacemos que verifique el error de acuerdo a las validaciones
        ;
    }

    function test_title_must_be_4_characters_min()
    {
        Livewire::test('article-form')
            ->set('article.title', 'Art')
            ->set('article.content', 'Article content')
            ->call('save')
            ->assertHasErrors(['article.title' => 'min'])
        ;
    }

    function test_content_is_required()
    {
        Livewire::test('article-form')
            ->set('article.title', 'New article')
            ->call('save')
            ->assertHasErrors(['article.content' => 'required'])
        ;
    }

    function test_real_time_validation_works()
    {
        Livewire::test('article-form')
            ->set('article.title', '')
            ->assertHasErrors(['article.title' => 'required'])
            ->set('article.title', 'New')
            ->assertHasErrors(['article.title' => 'min'])
            ->set('article.title', 'New article')
            ->assertHasNoErrors('article.title')
        ;
    }

    function test_real_time_validation_works_for_content()
    {
        Livewire::test('article-form')
            ->set('article.content', '')
            ->assertHasErrors(['article.content' => 'required'])
            ->set('article.content', 'Article content')
            ->assertHasNoErrors('article.content')
        ;
    }
}
