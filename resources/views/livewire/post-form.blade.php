<div>
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

