<div>
   @if (count($additionalPhotos))
       @foreach ($additionalPhotos as $item)
           <img width="150px" src={{ url('storage/additional_photos/'. $item->filename)}} alt=""> <br/><br/>
       @endforeach
   @else
       <p>No additionnal photos for this post</p>
   @endif
</div>
