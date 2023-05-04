<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class ArticleDeleteModal extends Component
{
    protected $listeners = ['confirmArticleDeletion'];

    public $article;

    public $showDeleteModal = false;

    public function confirmArticleDeletion($article)
    {
        if ($this->article->id === $article['id']) {
            $this->showDeleteModal = true;
        }
    }

    public function delete()
    {
        Storage::disk('public')->delete($this->article->image);

        $this->article->delete();

        session()->flash('flash.bannerStyle', 'danger');
        session()->flash('flash.banner', __('Article deleted'));

        $this->redirect(route('articles.index'));
    }

    public function render()
    {
        return view('livewire.article-delete-modal');
    }
}
