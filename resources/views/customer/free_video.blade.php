@extends('customer.training_center.layouts.app')

@section('content')
<div class="training-center-imgs-container margin-top">
    <div class="section-header">
        <div>
            <p>Free Videos</p>
            <div class="section-header-underline">
    
            </div>
        </div>
        
        
    </div>
    <div class="container">
        <div class="row d-flex justify-content-center">
          @forelse ($videos as $video)
            <div class="col-lg-4 col-md-6 col-sm-12 ">
                
                <div class="card shadow" style="width: 25rem; height: 20rem;">
                
                    <div class="card-body">
                      <video controls class= "w-100" style="height:100%;">
                        <source src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/free_video/{{$video->video}}" type="video/mp4">
                      </video>
                    </div>
                    <div class="card-footer text-muted">
                      <p class="">{{$video->name}}</p>
                    </div>
                    
                  </div>

            </div>
            
            @empty
            <p class="text-secondary p-1">No Video Found</p>
          @endforelse
            
            

           
        </div>
    </div>

</div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@endpush
