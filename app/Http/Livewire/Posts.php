<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use phpDocumentor\Reflection\Types\This;

class Posts extends Component
{
    protected $fillable = ['title','content','user_id','feature_image'];

    use WithPagination;
    protected $paginationTheme = 'bootstrap'; 
    public $action;
    public $selectedItem;


    protected $listeners = [
        'refreshParent' =>'$refresh'
    ];

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;

        if($action == 'delete') {
            // this will show the modal on the frontend
            $this->dispatchBrowserEvent('openDeleteModal');
        }
        elseif($action =='showPhotos') {
            //  Pass the current select Item
            $this->emit('getPostId', $this->selectedItem);


            // show the modal that shos additional photos
            $this->dispatchBrowserEvent('openModalShowPhotos');
        }
        else{
            $this->emit('getModelId', $this->selectedItem);
            $this->dispatchBrowserEvent('openModal');
        }
    }

    public function delete()
    {
        Post::destroy($this->selectedItem);
        $this->dispatchBrowserEvent('closeDeleteModal');
    }
    public function render()
    {
        return view('livewire.posts',[
            'posts' => Post::where('user_id','=', auth()->user()->id)->paginate(3)
        ]);
    }
}
