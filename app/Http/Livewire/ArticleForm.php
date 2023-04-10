<?php

namespace App\Http\Livewire;

use App\Models\Article;
use Livewire\Component;

class ArticleForm extends Component
{
    public $title;
    public $content;

    protected $rules = [
        'title' => ['required', 'min:4'],
        'content' => ['required']
    ];

    /**
     * Esta función toma como parámetro el nombre
     * del campo que estamos modificando
     *
     * @param [string] $propertyName
     * @return void
     */
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        # Validamos de la misma forma que con blade
        // $this->validate([
        //     'title' => ['required'],
        //     'content' => ['required'],
        // ]);

        # Guardando información creando una nueva instancia
        // $article = new Article;

        // $article->title = $this->title;
        // $article->content = $this->content;
        // $article->save();

        # Esto devuelve todas las propiedades a su valor inicial
        // $this->reset();

        Article::create($this->validate());

        session()->flash('status', __('Article created'));

        $this->redirectRoute('articles.index');
    }

    public function render()
    {
        return view('livewire.article-form');
    }
}
