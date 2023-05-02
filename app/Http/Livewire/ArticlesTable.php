<?php

namespace App\Http\Livewire;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;

class ArticlesTable extends Component
{
    use WithPagination;

    public $search = "";

    public function render()
    {
        $articles = Article::where('title', 'like', "%{$this->search}%")->latest()->paginate(10);
        return view('livewire.articles-table', compact('articles'));
    }
}
