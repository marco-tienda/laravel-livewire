<?php

namespace Tests\Feature\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticlesTest extends TestCase
{
    public function test_articles_component_renders_properly()
    {
        $this->get('/')->assertSeeLivewire('articles');
    }
}
