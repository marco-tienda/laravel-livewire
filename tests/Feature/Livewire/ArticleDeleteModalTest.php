<?php

namespace Tests\Feature\Livewire;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ArticleDeleteModalTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
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

        Livewire::actingAs($user)->test('article-delete-modal', ['article' => $article])
            ->call('delete')
            ->assertSessionHas('flash.bannerStyle', 'danger')
            ->assertSessionHas('flash.banner')
            ->assertRedirect(route('articles.index'))
        ;

        Storage::disk('public')->assertMissing($imagePath);

        $this->assertDatabaseCount('articles', 0);
    }

}
