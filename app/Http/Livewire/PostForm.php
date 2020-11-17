<?php

namespace App\Http\Livewire;

use App\Models\AdditionalPhotos;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class PostForm extends Component
{
    use WithPagination;
    use WithFileUploads;


    protected $paginationTheme = 'bootstrap';

    public $title;
    public $content;
    public $modelId;
    public $featureImage;
    public $additionalPhotos;

    protected $listeners = [
        'getModelId',
        'forcedCloseModal'
    ];

    protected $rules = [
        'title' => 'required|min:10|max:20',
        'content' => 'required',
        'featureImage' => 'image' // Validates jpeg,png, gif. and other image format
        
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
        $valideData = $this->validate();

        $data = [
            'title' => $this->title,
            'content' => $this->content,
            'user_id' => auth()->user()->id,
            'feature_image' => $this->featureImage->hashName(),
        ];

        // Validation for the additionnal photos
        if (!empty($this->additionalPhotos)){
           $valideData = array_merge($valideData,
            [
                'additionalPhotos.*' =>'image'
            ]);
        }

        if (!empty($this->featureImage)) {
            $this->featureImage->store('public/photos');
        }


        if ($this->modelId) {
            Post::find($this->modelId)->update($data);
            $postInstanceId = $this->modelId;
        } else {
            $postInstance = Post::create($data);
            $postInstanceId = $postInstance->id;
        }

        // Upload the images
        if (!empty($this->additionalPhotos)) {
            foreach ($this->additionalPhotos as $photo) {
                $photo->store('public/additional_photos');

                //  Save the filename in the additional_photos table
                AdditionalPhotos::create([
                    'post_id' => $postInstanceId,
                    'filename' => $photo->hashName()
                ]);
            }
        }

        $this->emit('refreshParent');
        $this->dispatchBrowserEvent('closeModal');
        $this->cleanVars();
    }

    public function forcedCloseModal()
    {
        // this is to reset our public variables
        $this->cleanVars();

        // These will reset our error bags
        $this->resetErrorBag();
        $this->resetValidation();
    }


    private function cleanVars()
    {
        $this->modelId = null;
        $this->title = null;
        $this->content = null;
        $this->featureImage = null;
        $this->additionalPhotos = null;
    }

    public function render()
    {
        return view('livewire.post-form');
    }
}
