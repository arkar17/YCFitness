<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!--iconify-->
    <script src="https://code.iconify.design/iconify-icon/1.0.0/iconify-icon.min.js"></script>

    <!--global css-->
    {{-- <link href="{{ asset('css/trainer/globals.css')}}" rel="stylesheet"/> --}}
    <link href={{ asset('css/globals.css')}} rel="stylesheet"/>
    <link href="{{asset('css/trainer/trainerTrainingCenter.css')}}" rel="stylesheet" />

    <title>YC-Trainer</title>


  </head>
  <body class="customer-loggedin-bg">
    <script>
        const theme = localStorage.getItem('theme') || 'light';
        console.log(theme)
        document.documentElement.setAttribute('data-theme', theme);
    </script>

    <div class="nav-overlay">
    </div>

    @include('trainer.layouts.header')
    <!--theme-->
    <script src="{{asset('js/theme.js')}}"></script>
 <!--create gp modal-->
 <div class="modal fade" id="CreateGroupModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header  customer-transaction-modal-header">
          <h5 class="modal-title text-center" id="exampleModalLabel">Create Group</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearCreateGroupInputs()"></button>
        </div>
        <div class="modal-body">
         <form class="create-group-form" action="{{route('trainer.group.create')}}" method="POST">
            @method('POST')
            @csrf
            {{-- <input type="hidden" name="trainer_id" value="{{auth()->user()->id}}"> --}}
            <div class="create-group-name create-group-input">
                <p>Group Name</p>
                <input type="text" name="group_name" required>
            </div>
            <div class="create-group-member-type create-group-input">
                <p>Member Type</p>
                <select name="member_type" class="@error('member_type') is-invalid @enderror" required>
                    <option value="">Choose Member Type</option>
                    @foreach ($members as $member)
                    <option value="{{$member->member_type}}">{{$member->member_type}}</option>
                    @endforeach
                </select>
            </div>
            <div class="create-group-member-type create-group-input">
                <p>Level</p>
                <select name="member_type_level" class="@error('member_type_level') is-invalid @enderror" required>
                    <option value="">Choose Level</option>
                    <option value="beginner">Beginner</option>
                    <option value="advanced">Advanced</option>
                    <option value="professional">Professional</option>
                </select>
                @error('member_type_level')
                        <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="create-group-gender create-group-input">
                <p>Gender</p>
                <select name="gender" class="@error('gender') is-invalid @enderror" required>
                    <option value="">Choose Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            <div class="create-group-group-type create-group-input">
                <p>Group Type</p>
                <select name="group_type" class="@error('gender') is-invalid @enderror" id="group_type" required>
                    <option value="">Choose Group Type</option>
                    <option value="weight gain">Weight Gain</option>
                    <option value="body beauty">Body Beauty</option>
                    <option value="weight loss">Weight Loss</option>
                </select>
            </div>

            <div class="create-group-form-btns-contaier">
                <button type="submit" class="customer-primary-btn">Confirm</button>
                <button type="reset" class="customer-secondary-btn" data-bs-dismiss="modal" aria-label="Close" onclick="clearCreateGroupInputs()">Cancel</button>
            </div>
         </form>

        </div>

      </div>
    </div>
</div>
    <div class="customer-main-content-container">
        <div class="trainer-main-content-container">
            <button data-bs-toggle="modal" data-bs-target="#CreateGroupModal" class="trainer-create-gp-modal-btn customer-primary-btn">
                <iconify-icon icon="akar-icons:circle-plus" class="trainer-create-gp--modal-btn-icon"></iconify-icon>
                <p>Group</p>
            </button>
            @yield('content')

        </div>
    </div>


    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.1.3/axios.min.js" integrity="sha512-0qU9M9jfqPw6FKkPafM3gy2CBAvUWnYVOfNPDYKVuRTel1PrciTj+a9P3loJB+j0QmN2Y0JYQmkBBS8W+mbezg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
     {{-- emoji --}}
     <script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@3.0.3/dist/index.min.js"></script>

      {{-- Sweet Alert --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@3.0.3/dist/index.min.js"></script>

     <!--nav bar-->
     <script src={{asset('js/navBar.js')}}></script>
    @stack('scripts')






    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/emoji-picker/1.1.5/js/emoji-picker.min.js" integrity="sha512-EDnYyP0SRH/j5K7bYQlIQCwjm8dQtwtsE+Xt0Oyo9g2qEPDlwE+1fbvKqXuCoMfRR/9zsjSBOFDO6Urjefo28w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <!-- <script src="../js/emoji.js"></script> -->
    <script>

        function clearCreateGroupInputs(){
            const inputs = document.querySelectorAll(".create-group-form input"+",.create-group-form select")
            // console.log(inputs)
            for(var i = 0;i < inputs.length;i++){
                // console.log("hi")
                // console.log(inputs[i])
                inputs[i].value = ""
            }
        }

    </script>
  </body>
</html>


