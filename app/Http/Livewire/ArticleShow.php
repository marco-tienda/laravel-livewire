<?php

namespace App\Http\Livewire;

use App\Models\Article;
use Livewire\Component;

class ArticleShow extends Component
{
    public Article $article;

    /**
     * Recibimos el párametro a través del método
     * mount, haciendo uso del Type Hint. Sin embargo
     * esto puede ser omitido usando el tipado en nuestras
     * propiedades publicas.
     *
     * @param Article $article
     * @return void
     */
    // public function mount(Article $article)
    // {
    //     $this->article = $article;
    // }

    public function render()
    {
        return view('livewire.article-show');
    }
}
