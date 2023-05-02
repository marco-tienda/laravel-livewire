<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleFormTest extends TestCase
{
    use RefreshDatabase;

    function test_guests_cannot_create_or_update_articles()
    {
        $this->get(route('articles.create'))
            ->assertRedirect('login');

        $article = Article::factory()->create();

        $this->get(route('articles.edit', $article))
            ->assertRedirect('login');
    }

    function test_article_form_renders_properly()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('articles.create'))
            ->assertSeeLivewire('article-form')
            ->assertDontSeeText(__('Delete'));

        $article = Article::factory()->create();

        $this->actingAs($user)->get(route('articles.edit', $article))
            ->assertSeeLivewire('article-form');
    }

    function test_blade_template_is_wired_properly()
    {
        Livewire::test('article-form')
            ->assertSeeHtml('wire:submit.prevent="save"')
            ->assertSeeHtml('wire:model="article.title"')
            ->assertSeeHtml('wire:model="article.slug"');
    }

    function test_can_create_new_articles()
    {
        /**
         * Creamos un disco en memoria para probar
         * la carga de imágenes
         */
        Storage::fake('public');

        /**
         * Creamos una imagen falsa, a su vez guardamos
         * el path de donde se está almacenando dicha imagen
         */
        $image = UploadedFile::fake()->image('post-image.png');

        $user = User::factory()->create();

        $category = Category::factory()->create();

        Livewire::actingAs($user)->test('article-form')
            ->set('image', $image)
            ->set('article.title', 'New article')
            ->set('article.slug', 'new-article')
            ->set('article.content', 'Article content')
            ->set('article.category_id', $category->id)
            ->call('save')
            ->assertSessionHas('status')
            ->assertRedirect(route('articles.index'));

        $this->assertDatabaseHas('articles', [
            'image' => $imagePath = Storage::disk('public')->files()[0],
            'title' => 'New article',
            'slug' => 'new-article',
            'content' => 'Article content',
            'category_id' => $category->id,
            'user_id' => $user->id
        ]);

        Storage::disk('public')->assertExists($imagePath);
    }

    function test_can_update_articles()
    {
        $article = Article::factory()->create();

        $user = User::factory()->create();

        Livewire::actingAs($user)->test('article-form', ['article' => $article])
            ->assertSet('article.title', $article->title)
            ->assertSet('article.slug', $article->slug)
            ->assertSet('article.content', $article->content)
            ->assertSet('article.category_id', $article->category->id)
            ->set('article.title', 'Updated title')
            ->set('article.slug', 'updated-slug')
            ->call('save')
            ->assertSessionHas('status')
            ->assertRedirect(route('articles.index'));

        # Verificamos que solo se cree un registro
        $this->assertDatabaseCount('articles', 1);

        $this->assertDatabaseHas('articles', [
            'title' => 'Updated title',
            'slug' => 'updated-slug',
            'user_id' => $user->id,
        ]);
    }

    function test_can_delete_articles()
    {
        Storage::fake();

        $imagePath = UploadedFile::fake()
            ->image('image.png')
            ->store('/', 'public');

        $article = Article::factory()->create([
            'image' => $imagePath
        ]);

        $user = User::factory()->create();

        Livewire::actingAs($user)->test('article-form', ['article' => $article])
            ->call('delete')
            ->assertSessionHas('status')
            ->assertRedirect(route('articles.index'))
        ;

        Storage::disk('public')->assertMissing($imagePath);

        $this->assertDatabaseCount('articles', 0);
    }

    function test_can_update_articles_image()
    {
        Storage::fake('public');

        $oldImage     = UploadedFile::fake()->image('old-image.png');
        $oldImagePath = $oldImage->store('/', 'public');
        $newImage     = UploadedFile::fake()->image('new-image.png');

        $article = Article::factory()->create([
            'image' => $oldImagePath
        ]);

        $user = User::factory()->create();

        Livewire::actingAs($user)->test('article-form', ['article' => $article])
            ->set('image', $newImage)
            ->call('save')
            ->assertSessionHas('status')
            ->assertRedirect(route('articles.index'));

        Storage::disk('public')
            ->assertExists($article->fresh()->image)
            ->assertMissing($oldImagePath);
    }

    function test_image_is_required()
    {
        Livewire::test('article-form')
            ->set('article.title', 'Article title')
            ->set('article.content', 'Article content')
            ->call('save')
            ->assertHasErrors(['image' => 'required']) # Hacemos que verifique el error de acuerdo a las validaciones
            ->assertSeeHtml(__('validation.required', ['attribute' => 'image']));
    }

    function test_field_must_be_of_type_image()
    {
        Livewire::test('article-form')
            ->set('image', 'string-not-allowed')
            ->call('save')
            ->assertHasErrors(['image' => 'image']) # Hacemos que verifique el error de acuerdo a las validaciones
            ->assertSeeHtml(__('validation.image', ['attribute' => 'image']));
    }

    function test_image_must_be_2mb_max()
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('post-image.png')->size(3000);

        Livewire::test('article-form')
            ->set('image', $image)
            ->call('save')
            ->assertHasErrors(['image' => 'max']) # Hacemos que verifique el error de acuerdo a las validaciones
            ->assertSeeHtml(__('validation.max.file', [
                'attribute' => 'image',
                'max' => '2048'
            ]));
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
            ->assertSeeHtml(__('validation.required', ['attribute' => 'slug']));
    }

    function test_category_is_required()
    {
        Livewire::test('article-form')
            ->set('article.title', 'New title')
            ->set('article.slug', 'new-title')
            ->set('article.content', 'Article content')
            ->set('article.category_id', null)
            ->call('save')
            ->assertHasErrors(['article.category_id' => 'required']) # Hacemos que verifique el error de acuerdo a las validaciones
            ->assertSeeHtml(__('validation.required', ['attribute' => 'category id']));
    }

    function test_category_must_exist_in_database()
    {
        Livewire::test('article-form')
            ->set('article.title', 'New title')
            ->set('article.slug', 'new-title')
            ->set('article.content', 'Article content')
            ->set('article.category_id', 1)
            ->call('save')
            ->assertHasErrors(['article.category_id' => 'exists']) # Hacemos que verifique el error de acuerdo a las validaciones
            ->assertSeeHtml(__('validation.exists', ['attribute' => 'category id']));
    }

    function test_can_create_new_category()
    {
        Livewire::test('article-form')
            ->call('openCategoryForm')
            ->set('newCategory.name', 'Laravel')
            ->assertSee('newCategory.slug', 'laravel')
            ->call('saveNewCategory')
            ->assertSee('article.category_id', Category::first()->id)
            ->assertSee('showCategoryModal', false)
        ;

        $this->assertDatabaseCount('categories', 1);
    }

    function test_new_category_name_is_required()
    {
        Livewire::test('article-form')
            ->call('openCategoryForm')
            ->set('newCategory.slug', 'laravel')
            ->call('saveNewCategory')
            ->assertHasErrors(['newCategory.name' => 'required'])
            ->assertSeeHtml(__('validation.required', ['attribute' => 'name']))
        ;
    }

    function test_new_category_name_must_be_unique()
    {
        $category = Category::factory()->create();

        Livewire::test('article-form')
            ->call('openCategoryForm')
            ->set('newCategory.name', $category->name)
            ->set('newCategory.slug', 'laravel')
            ->call('saveNewCategory')
            ->assertHasErrors(['newCategory.name' => 'unique'])
            ->assertSeeHtml(__('validation.unique', ['attribute' => 'name']))
        ;
    }

    function test_new_category_slug_must_be_unique()
    {
        $category = Category::factory()->create();

        Livewire::test('article-form')
            ->call('openCategoryForm')
            ->set('newCategory.name', 'Laravel')
            ->set('newCategory.slug', $category->slug)
            ->call('saveNewCategory')
            ->assertHasErrors(['newCategory.slug' => 'unique'])
            ->assertSeeHtml(__('validation.unique', ['attribute' => 'slug']))
        ;
    }

    function test_new_category_slug_is_required()
    {
        Livewire::test('article-form')
            ->call('openCategoryForm')
            ->set('newCategory.name', 'Laravel')
            ->set('newCategory.slug', null)
            ->call('saveNewCategory')
            ->assertHasErrors(['newCategory.slug' => 'required'])
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
            ->assertSeeHtml(__('validation.unique', ['attribute' => 'slug']));
    }

    function test_slug_must_only_contain_letters_numbers_dashes_and_underscores()
    {
        Livewire::test('article-form')
            ->set('article.title', 'New title')
            ->set('article.slug', 'new-article#$%&')
            ->set('article.content', 'Article content')
            ->call('save')
            ->assertHasErrors(['article.slug' => 'alpha_dash']) # Hacemos que verifique el error de acuerdo a las validaciones
            ->assertSeeHtml(__('validation.alpha_dash', ['attribute' => 'slug']));
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
            ->assertHasErrors(['article.title' => 'min']);
    }

    function test_content_is_required()
    {
        Livewire::test('article-form')
            ->set('article.title', 'New article')
            ->call('save')
            ->assertHasErrors(['article.content' => 'required']);
    }

    function test_real_time_validation_works()
    {
        Livewire::test('article-form')
            ->set('article.title', '')
            ->assertHasErrors(['article.title' => 'required'])
            ->set('article.title', 'New')
            ->assertHasErrors(['article.title' => 'min'])
            ->set('article.title', 'New article')
            ->assertHasNoErrors('article.title');
    }

    function test_real_time_validation_works_for_content()
    {
        Livewire::test('article-form')
            ->set('article.content', '')
            ->assertHasErrors(['article.content' => 'required'])
            ->set('article.content', 'Article content')
            ->assertHasNoErrors('article.content');
    }

    function test_slug_is_generated_automatically()
    {
        Livewire::test('article-form')
            ->set('article.title', 'Nuevo titulo')
            ->assertSet('article.slug', 'nuevo-titulo');
    }
}
