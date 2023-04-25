<?php

namespace App\Http\Livewire;

use App\Models\Article;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;

class ArticleForm extends Component
{
    use WithFileUploads;

    /**
     * Usando el modelo como tipado evitamos
     * tener que escribir todas las propiedades
     * que necesitamos.
     *
     * @var Article
     */
    public Article $article;
    public $image;

    protected function rules()
    {
        return [
            'image' => ['image', 'max:2048'],
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
        /**
         * Validaciones
         */
        $this->validate();

        /**
         * Si existe una imagen cargandose
         * Almacenamos el path de donde se está
         * almacenando nuestra imagen y guardamos la misma
         * en el disco indicado.
         */
        if ($this->image) {
            $this->article->image = $this->uploadImage();
        }

        /**
         * Almacenamos el id del usuario autenticado
         * para que este tenga acceso a los articulos
         * creados por el mismo
         */
        $this->article->user_id = auth()->id();
        // $this->article->user_id = Auth::user()->id;

        /**
         * Guardamos los valores del articulo que
         * vienen desde el formulario
         */
        $this->article->save();

        /**
         * Enviamos una variable de session para
         * confirmar que los datos se han guardado
         * correctamente.
         */
        session()->flash('status', __('Article saved'));

        /**
         * Reedirigimos al panel principal
         */
        $this->redirectRoute('articles.index');
    }

    protected function uploadImage()
    {
        if ($oldImage = $this->article->image) {
            Storage::disk('public')->delete($oldImage);
        }

        return $this->image->store('/', 'public');
    }

    public function render()
    {
        return view('livewire.article-form');
    }
}
