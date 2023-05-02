<?php

namespace App\Http\Livewire;

use App\Models\Article;
use Livewire\Component;

class ArticlesTable extends Component
{
    public $articles;
    public $search = "";

    public function render()
    {
        return view('livewire.articles-table', [
            $this->articles = Article::where('title', 'like', "%{$this->search}%")->latest()->get()
        ])->layout('layouts.guest');
    }
}
