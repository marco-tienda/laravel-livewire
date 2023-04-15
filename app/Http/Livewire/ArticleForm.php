<?php

namespace App\Http\Livewire;

use App\Models\Article;
use Livewire\Component;

class ArticleForm extends Component
{
    /**
     * Usando el modelo como tipado evitamos
     * tener que escribir todas las propiedades
     * que necesitamos.
     *
     * @var Article
     */
    public Article $article;

    protected $rules = [
        # modelo.propiedad
        'article.title' => ['required', 'min:4'],
        'article.slug' => ['required', 'unique:articles,slug'],
        'article.content' => ['required'],
    ];

    public function mount(Article $article)
    {
        /**
         * Inicializamos una nueva instancia de nuestro modelo
         * ya que si no lo hacemos podríamos tener un error por
         * tratar de acceder a una propiedad sin antes inicializar
         * una instancia del modelo
         */
        // $this->article = new Article;

        # Pasamos en el mount el tipo de dato
        $this->article = $article;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        # Validaciones del formulario
        $this->validate();

        # Guardamos la información
        $this->article->save();

        session()->flash('status', __('Article saved'));

        $this->redirectRoute('articles.index');
    }

    public function render()
    {
        return view('livewire.article-form');
    }
}
