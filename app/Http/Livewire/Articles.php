<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Articles extends Component
{
    public $articles;
    public $search = "";

    public function render()
    {
        /**
         * Pasando el valor de nuestra propiedad de esta forma
         * podemos evitar el uso del método mount.
         *
         * El método layout nos permite renderizar solo la vista
         */
        return view('livewire.articles', [
            $this->articles = \App\Models\Article::where('title', 'like', "%{$this->search}%")->latest()->get()
        ]);
    }
}
