<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class PostForm extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; 

    public $title;
    public $content;
    public $modelId;

    protected $listeners = [
        'getModelId'
    ];
    
    protected $rules = [
        'title' => 'required|min:10|max:20',
        'content' => 'required',
    ];

    public function getModelId($modelId)
    {
        $this->modelId = $modelId;
        $model = Post::find($this->modelId);
        $this->title = $model->title;
        $this->content = $model->content;
    }
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $data = $this->validate();
        $data = [
            'title' => $this->title,
            'content' => $this->content,
            'user_id' => auth()->user()->id,
        ];

        if ($this->modelId) {
            Post::find($this->modelId)->update($data);
        }else {
            Post::create($data);
        }

        $this->emit('refreshParent');
        $this->dispatchBrowserEvent('closeModal');
        $this->cleanVars();
    }

    private function cleanVars()
    {
        $this->modelId = null;
        $this->title = null;
        $this->content = null;
    }

    public function render()
    {
        return view('livewire.post-form');
    }
}
