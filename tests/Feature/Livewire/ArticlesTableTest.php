<?php

namespace Tests\Feature\Livewire;

use App\Models\User;
use Tests\TestCase;

class ArticlesTableTest extends TestCase
{
    public function test_articles_component_renders_properly()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('articles.index'))
            ->assertSeeLivewire('articles-table');
    }
}
