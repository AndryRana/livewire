<div>
    <label for=""> Featured Image</label>  <br/>
    <input type="file" wire:model="featureImage"/>
    <br/>  <br/>

    <label >Photos</label><br/>
    <input type="file" wire:model="additionalPhotos" multiple />
    <br/><br/>

    <label for="">Title</label>
    <input wire:model="title" type="title" class=" form-control">
    @error('title')
    <p class="error text-danger">{{ $message }}</p>
    @enderror

    <label for="">Content</label>
    <textarea wire:model="content" type="text" class=" form-control"></textarea>
    @error('content')
    <p class="error">{{ $message }}</p>
    @enderror
    <br/>
    <button wire:click="save" class="btn btn-sm btn-primary">Save</button>
   
</div>

