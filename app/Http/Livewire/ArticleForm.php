<?php

namespace App\Http\Livewire;

use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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

    protected function rules()
    {
        return [
            'article.title' => ['required', 'min:4'],
            'article.slug' => [
                'required',
                'alpha_dash',
                Rule::unique('articles', 'slug')->ignore($this->article)
                // 'unique:articles,slug,'.$this->article->id
            ],
            'article.content' => ['required'],
        ];
    }

    // protected $rules = [
    //     # modelo.propiedad
    // ];

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

    public function updatedArticleTitle($title)
    {
        $this->article->slug = Str::slug($title);
    }

    public function save()
    {
        # Validaciones del formulario
        $this->validate();

        // Auth::user()->articles()->save($this->article);

        /**
         * Equivalente a la línea de arriba
         */
        $this->article->user_id = auth()->id();
        $this->article->save();

        session()->flash('status', __('Article saved'));

        $this->redirectRoute('articles.index');
    }

    public function render()
    {
        return view('livewire.article-form');
    }
}
