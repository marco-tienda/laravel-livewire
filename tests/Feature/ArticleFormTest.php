<?php

namespace Tests\Feature\Livewire;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ArticleFormTest extends TestCase
{
    use RefreshDatabase;

    function test_guests_cannot_create_or_update_articles()
    {
        // $this->withoutExceptionHandling();

        $this->get(route('articles.create'))
            ->assertRedirect('login')
        ;

        $article = Article::factory()->create();

        $this->get(route('articles.edit', $article))
            ->assertRedirect('login')
        ;
    }

    function test_article_form_renders_properly()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('articles.create'))->assertSeeLivewire('article-form');

        $article = Article::factory()->create();

        $this->actingAs($user)->get(route('articles.edit', $article))
            ->assertSeeLivewire('article-form');
    }

    function test_blade_template_is_wired_properly()
    {
        Livewire::test('article-form')
            ->assertSeeHtml('wire:submit.prevent="save"')
            ->assertSeeHtml('wire:model="article.title"')
            ->assertSeeHtml('wire:model="article.slug"')
            ->assertSeeHtml('wire:model="article.content"')
        ;
    }

    function test_can_create_new_articles()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)->test('article-form')
            ->set('article.title', 'New article')
            ->set('article.slug', 'new-article')
            ->set('article.content', 'Article content')
            ->call('save')
            ->assertSessionHas('status')
            ->assertRedirect(route('articles.index'))
        ;

        $this->assertDatabaseHas('articles', [
            'title' => 'New article',
            'slug' => 'new-article',
            'content' => 'Article content',
            'user_id' => $user->id
        ]);
    }

    function test_can_update_articles()
    {
        $article = Article::factory()->create();

        $user = User::factory()->create();

        Livewire::actingAs($user)->test('article-form', ['article' => $article])
            ->assertSet('article.title', $article->title)
            ->assertSet('article.slug', $article->slug)
            ->assertSet('article.content', $article->content)
            ->set('article.title', 'Updated title')
            ->set('article.slug', 'updated-slug')
            ->call('save')
            ->assertSessionHas('status')
            ->assertRedirect(route('articles.index'))
        ;

        # Verificamos que solo se cree un registro
        $this->assertDatabaseCount('articles', 1);

        $this->assertDatabaseHas('articles', [
            'title' => 'Updated title',
            'slug' => 'updated-slug',
            'user_id' => $user->id,
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

    function test_slug_is_required()
    {
        Livewire::test('article-form')
            ->set('article.title', 'New title')
            ->set('article.slug', null)
            ->set('article.content', 'Article content')
            ->call('save')
            ->assertHasErrors(['article.slug' => 'required']) # Hacemos que verifique el error de acuerdo a las validaciones
            ->assertSeeHtml(__('validation.required', ['attribute' => 'slug']))
        ;
    }

    function test_slug_must_be_unique()
    {
        $article = Article::factory()->create();

        Livewire::test('article-form')
            ->set('article.title', 'New title')
            ->set('article.slug', $article->slug)
            ->set('article.content', 'Article content')
            ->call('save')
            ->assertHasErrors(['article.slug' => 'unique']) # Hacemos que verifique el error de acuerdo a las validaciones
            ->assertSeeHtml(__('validation.unique', ['attribute' => 'slug']))
        ;
    }

    function test_slug_must_only_contain_letters_numbers_dashes_and_underscores()
    {
        Livewire::test('article-form')
            ->set('article.title', 'New title')
            ->set('article.slug', 'new-article#$%&')
            ->set('article.content', 'Article content')
            ->call('save')
            ->assertHasErrors(['article.slug' => 'alpha_dash']) # Hacemos que verifique el error de acuerdo a las validaciones
            ->assertSeeHtml(__('validation.alpha_dash', ['attribute' => 'slug']))
        ;
    }

    function test_unique_rule_should_be_ignored_when_updating_the_same_slug()
    {
        $article = Article::factory()->create();

        $user = User::factory()->create();

        Livewire::actingAs($user)->test('article-form', ['article' => $article])
            ->set('article.title', 'New title')
            ->set('article.slug', $article->slug)
            ->set('article.content', 'Article content')
            ->call('save')
            ->assertHasNoErrors(['article.slug' => 'unique']) # Hacemos que verifique el error de acuerdo a las validaciones
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

    function test_slug_is_generated_automatically()
    {
        Livewire::test('article-form')
            ->set('article.title', 'Nuevo titulo')
            ->assertSet('article.slug', 'nuevo-titulo')
        ;
    }
}
