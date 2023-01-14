@extends('layouts.app')

@section('styles')
    <style>
        .swal2-popup {
            display: none;
            position: relative;
            box-sizing: border-box;
            grid-template-columns: minmax(0, 100%);
            width: 40em !important;
            max-width: 100%;
            padding: 0 0 1.25em;
            border: none;
            border-radius: 5px;
            background: #fff;
            color: #545454;
            font-family: inherit;
            font-size: 1rem;
        }

        .form-label {
            font-size: 14px;
        }
    </style>
@endsection
@section('member-active', 'active')
@section('content')
    @if (Session::has('success'))
    <div id="hide">
        <h4 class="main-cash-alert"> {{ Session::get('success') }} <span class="closeBtn">X</span> </h4>
    </div>
    @endif
    <div class="col-md-11 mx-auto">
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-center mb-0">Edit Member</h2>
        </div>

        <div class="col-12 card p-4 mb-5">
            <form action="{{route('member.user_member.update',$user->id)}}" method="POST">
                @csrf
                @method('POST')
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <i class="fa-solid fa-user"></i>
                    </span>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Username" aria-label="Username" name="name" value="{{$user->name}}">
                    <span class="input-group-text">
                        <i class="fa-solid fa-phone"></i>
                    </span>
                    <input type="integer" class="form-control @error('phone') is-invalid @enderror" placeholder="Phone" aria-label="Phone" name="phone" value="{{$user->phone}}">
                    <span class="input-group-text">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input type="email" class="form-control" @error('email') is-invalid @enderror placeholder="Email" aria-label="Email" name="email" value="{{$user->email}}">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{$user->address}}">

                </div>
                <div class="mb-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                        <label for="from_date" class="col-form-label">Register Date</label>
                        <input type="date @error('from_date') is-invalid @enderror" id="from_date" class="form-control" name="from_date"  value="{{$user->from_date}}">

                        </div>
                        <div class="col-auto">
                            <label for="to_date" class="col-form-label">Expired Date</label>
                            <input type="date @error('to_date') is-invalid @enderror" id="to_date" class="form-control" name="to_date"  value="{{$user->to_date}}">

                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="row g-3 align-items-center">
                    <div class="col-auto">
                    <label for="gender" class="form-label">Gender</label>
                    <select id="gender" name="gender" class="form-select form-select-md mb-3 @error('gender') is-invalid @enderror" style="width:auto;">
                        <option value="male" {{ "male" == $user->gender ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ "female" == $user->gender ? 'selected' : '' }}>Female</option>
                    </select>
                    </div>
                    <div class="col-auto">
                    <label for="member_type" class="form-label">Member Type</label>
                    <select name="member_type" class="form-select form-select-md mb-3 @error('member_type') is-invalid @enderror" style="width:auto;">
                        @foreach ($members as $member)
                        <option value="{{ $member->id }}" {{ $user->member_type == $member->member_type ? 'selected' : '' }}>{{$member->member_type}} - {{$member->duration}}month</option>
                        @endforeach
                    </select>
                    </div>
                    <div class="col-auto">
                    <label for="membertype_level" class="form-label">Member Type Level</label>
                    <select name="membertype_level" class="form-select form-select-md mb-3 @error('membertype_level') is-invalid @enderror" style="width:auto;">
                        <option value="beginner" {{ "beginner" == $user->membertype_level ? 'selected' : '' }}>
                           beginner
                        </option>
                        <option value="advanced" {{ "advanced" == $user->membertype_level ? 'selected' : '' }}>
                            advanced
                         </option>
                         <option value="professional" {{ "professional" == $user->membertype_level ? 'selected' : '' }}>
                            professional
                         </option>
                    </select>
                    </div>

                    </div>
                </div>
                <div class="mb-3">
                    <div class="row g-3 align-items-center">
                            <div class="col-auto">
                            <label for="bmi" class="col-form-label">BMI</label>
                            <input type="integer @error('bmi') is-invalid @enderror" id="bmi" class="form-control" name="bmi"  value="{{$user->bmi}}" style="width: auto" readonly>

                            </div>
                            <div class="col-auto">
                                <label for="bfp" class="col-form-label">BFP</label>
                                <input type="integer @error('bfp') is-invalid @enderror" id="bfp" class="form-control" name="bfp"  value="{{$user->bfp}}" style="width: auto" readonly>

                            </div>
                            <div class="col-auto">
                                <label for="bmr" class="col-form-label">BMR</label>
                                <input type="integer @error('bmr') is-invalid @enderror" id="bmr" class="form-control" name="bmr"  value="{{$user->bmr}}" style="width: auto" readonly>

                                </div>
                            <div class="col-auto">
                                <label for="goal" class="form-label">Goal</label>
                                <input type="text" class="form-control @error('goal') is-invalid @enderror" id="goal" name="goal" value="{{$user->goal}}" readonly>

                            </div>
                            <div class="col-auto">
                                <label for="ideal_weight" class="col-form-label">Ideal Weight</label>
                                <input type="integer" id="ideal_weight" class="form-control" name="ideal_weight"  value="{{$user->ideal_weight}}" readonly>

                            </div>
                    </div>
                    </div>
                <div class="mb-3">
                <div class="row g-3 align-items-center">

                    <div class="col-auto">
                        <label for="weight" class="col-form-label">Weight</label>
                        <input type="integer @error('weight') is-invalid @enderror" id="weight" class="form-control" placeholder="weight(pound)" name="weight"  value="{{$user->weight}}" >

                    </div>
                    <div class="col-auto">
                        <label for="height" class="col-form-label">Height</label>
                        <input type="integer @error('height') is-invalid @enderror" id="height" class="form-control" placeholder="height(inches)" name="height"  value="{{$user->height}}" >

                        </div>
                    <div class="col-auto">
                        <label for="age" class="form-label">Age</label>
                        <input type="text" class="form-control @error('age') is-invalid @enderror" id="age" name="age" value="{{$user->age}}">

                    </div>
                    <div class="col-auto">
                        <label for="neck" class="col-form-label">Neck</label>
                        <input type="integer @error('neck') is-invalid @enderror" id="neck" class="form-control" placeholder="neck(inches)" name="neck"  value="{{$user->neck}}" >

                    </div>

                </div>
                </div>
                <div class="mb-3">
                    <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="waist" class="col-form-label">Waist</label>
                                <input type="integer @error('waist') is-invalid @enderror" id="waist" class="form-control" placeholder="waist(inches)" name="waist"  value="{{$user->waist}}" >

                            </div>
                            <div class="col-auto" id="female_hip">
                                <label for="hip" class="col-form-label">Hip</label>
                                <input type="integer @error('hip') is-invalid @enderror" id="hip" class="form-control" placeholder="hip(inches)" name="hip"  value="{{$user->hip}}" >

                            </div>
                            <div class="col-auto">
                                <label for="shoulders" class="col-form-label">Shoulder</label>
                                <input type="integer @error('shoulders') is-invalid @enderror" id="shoulders" class="form-control" name="shoulders"  value="{{$user->shoulders}}" >

                            </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label for="body_type" class="form-label">Body Type</label>
                            <select name="body_type" class="form-select form-select-md mb-3 @error('body_type') is-invalid @enderror" style="width:auto;">
                                <option value="Ectomorph" {{ "Ectomorph" == $user->body_type ? 'selected' : '' }}>Ectomorph</option>
                                <option value="Mesomorph" {{ "Mesomorph" == $user->body_type ? 'selected' : '' }}>Mesomorph</option>
                                <option value="Endomorph" {{ "Endomorph" == $user->body_type ? 'selected' : '' }}>Endomorph</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <label for="average_night" class="form-label">Average Night</label>
                            <select name="average_night" class="form-select form-select-md mb-3 @error('average_night') is-invalid @enderror" style="width:auto;">
                                <option value="minimal" {{ "minimal" == $user->average_night ? 'selected' : '' }}>less than 5 hours</option>
                                <option value="average" {{ "average" == $user->average_night ? 'selected' : '' }}>5 - 6 hours</option>
                                <option value="good" {{ "good" == $user->average_night ? 'selected' : '' }}>7 - 8 hours</option>
                                <option value="sleep hero" {{ "sleep hero" == $user->average_night ? 'selected' : '' }}>More than 8 hours</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <label for="physical_activity" class="form-label">Physical Activity</label>
                            <select name="physical_activity" id="physical_activity" class="form-select form-select-md mb-3 @error('physical_activity') is-invalid @enderror" style="width:auto;">
                                <option value="not much" {{ "not much" == $user->physical_activity ? 'selected' : '' }}>not much</option>
                                <option value="1 - 2 times a week" {{ "1 - 2 times a week" == $user->physical_activity ? 'selected' : '' }}>1 - 2 times a week</option>
                                <option value="3 - 5 times a week" {{ "3 - 5 times a week" == $user->physical_activity ? 'selected' : '' }}>3 - 5 times a week</option>
                                <option value="5 - 7 times a week" {{ "5 - 7 times a week" == $user->physical_activity ? 'selected' : '' }}>5 - 7 times a week</option>
                            </select>

                        </div>
                        <div class="col-auto">
                            <label for="diet_type" class="form-label">Diet Type</label>
                            <select name="diet_type" class="form-select form-select-md mb-3 @error('diet_type') is-invalid @enderror" style="width:auto;">
                                <option value="traditional" {{ "traditional" == $user->diet_type ? 'selected' : '' }}>traditional</option>
                                <option value="vagetarian" {{ "vagetarian" == $user->diet_type ? 'selected' : '' }}>vagetarian</option>
                                <option value="keto" {{ "keto" == $user->diet_type ? 'selected' : '' }}>keto</option>
                                <option value="pescatarian" {{ "pescatarian" == $user->diet_type ? 'selected' : '' }}>pescatarian</option>
                                <option value="vegan" {{ "vegan" == $user->diet_type ? 'selected' : '' }}>vegan</option>
                                <option value="keto vegan" {{ "keto vegan" == $user->diet_type ? 'selected' : '' }}>keto vegan</option>
                                <option value="lactose free" {{ "lactose free" == $user->diet_type ? 'selected' : '' }}>lactose free</option>

                            </select>

                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                        <label for="daily_life" class="form-label">Daily Life</label>
                        <select name="daily_life" class="form-select form-select-md mb-3 @error('daily_life') is-invalid @enderror" style="width:auto;">
                            <option value="at the office" {{ "at the office" == $user->daily_life ? 'selected' : '' }}>at the office</option>
                            <option value="walking daily" {{ "walking daily" == $user->daily_life ? 'selected' : '' }}>walking daily</option>
                            <option value="working physically" {{ "working physically" == $user->daily_life ? 'selected' : '' }}>working physically</option>
                            <option value="mostly at home" {{ "mostly at home" == $user->daily_life ? 'selected' : '' }}>mostly at home</option>
                        </select>
                        </div>
                        <div class="col-auto">
                        <label for="energy_level" class="form-label">Energy Level</label>
                        <select name="energy_level" class="form-select form-select-md mb-3 @error('energy_level') is-invalid @enderror" style="width:auto;">
                            <option value="even throughout the day" {{ "even throughout the day" == $user->energy_level ? 'selected' : '' }}>even throughout the day</option>
                            <option value="a dip in energy around lunch time" {{ "a dip in energy around lunch time" == $user->energy_level ? 'selected' : '' }}>a dip in energy around lunch time</option>
                            <option value="a nap after meals" {{ "a nap after meals" == $user->energy_level ? 'selected' : '' }}>a nap after meals</option>
                            <option value="mostly at home" {{ "mostly at home" == $user->energy_level ? 'selected' : '' }}>mostly at home</option>
                        </select>
                        </div>
                        <div class="col-auto">
                            <label for="activities" class="form-label">Activities</label>
                            <select name="activities" class="form-select form-select-md mb-3 @error('activities') is-invalid @enderror" style="width:auto;">
                                <option value="working out at home" {{ "working out at home" == $user->activities ? 'selected' : '' }}>working out at home</option>
                                <option value="working out at a gym" {{ "working out at a gym" == $user->activities ? 'selected' : '' }}>working out at a gym</option>
                                <option value="running" {{ "running" == $user->activities ? 'selected' : '' }}>running</option>
                                <option value="walking" {{ "walking" == $user->activities ? 'selected' : '' }}>walking</option>
                            </select>
                        </div>

                        <div class="col-auto">
                            <label for="hydration" class="form-label">Hydration</label>
                            <select name="hydration" class="form-select form-select-md mb-3 @error('hydration') is-invalid @enderror" style="width:auto;">
                                <option value="only coffee" {{ "only coffee" == $user->hydration ? 'selected' : '' }}>only coffee or tea</option>
                                <option value="about 2 gla" {{ "about 2 gla" == $user->hydration ? 'selected' : '' }}>about 2 glasses</option>
                                <option value="2 to 6 glas" {{ "2 to 6 glas" == $user->hydration ? 'selected' : '' }}>2 to 6 glasses</option>
                                <option value="more than 6" {{ "more than 6" == $user->hydration ? 'selected' : '' }}>more than 6 glasses</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="bad_habits" class="form-label">Bad Habits : </label>

                    @foreach (json_decode($user->bad_habits); as $s)

                    <input class="form-check-input" type="checkbox" id="{{$s}}" name="bad_habits[]" value="{{$s}}" checked/>
                    <label class="form-check-label" for="{{$s}}">{{$s}}</label>
                    @endforeach
                    <hr style="width:50%">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="i don't rest enough" name="most_attention_areas[]" value="i don't rest enough"  />
                        <label class="form-check-label" for="i don't rest enough">i don't rest enough</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="sweet tooth" name="most_attention_areas[]" value="sweet tooth" />
                        <label class="form-check-label" for="sweet tooth">sweet tooth</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="too much soda" name="most_attention_areas[]" value="too much soda" />
                        <label class="form-check-label" for="too much soda">too much soda</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="a lot of salty foods" name="most_attention_areas[]" value="a lot of salty foods" />
                        <label class="form-check-label" for="a lot of salty foods">a lot of salty foods</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="lat night snacks" name="most_attention_areas[]" value="late night snacks" />
                        <label class="form-check-label" for="late night snacks">late night snacks</label>
                    </div>

                    @error('bad_habits')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <br>
                <div class="mb-3">
                    <label for="most_attention_areas" class="form-label">Most Attention Areas : </label>

                    @foreach (json_decode($user->most_attention_areas); as $s)

                    <input class="form-check-input" type="checkbox" id="{{$s}}" name="most_attention_areas[]" value="{{$s}}" checked/>
                    <label class="form-check-label" for="{{$s}}">{{$s}}</label>
                    @endforeach
                    <hr style="width:50%">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="back" name="most_attention_areas[]" value="back"  />
                        <label class="form-check-label" for="back">back</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="chest" name="most_attention_areas[]" value="chest" />
                        <label class="form-check-label" for="chest">chest</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="arms" name="most_attention_areas[]" value="arms" />
                        <label class="form-check-label" for="arms">arms</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="belly" name="most_attention_areas[]" value="belly" />
                        <label class="form-check-label" for="belly">belly</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="butt" name="most_attention_areas[]" value="butt" />
                        <label class="form-check-label" for="butt">butt</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="legs" name="most_attention_areas[]" value="legs" />
                        <label class="form-check-label" for="legs">legs</label>
                    </div>
                    @error('most_attention_areas')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <br>
                <div class="mb-3">
                    <label for="physical_limitation" class="form-label">Physical Limitation : </label>

                    @foreach (json_decode($user->physical_limitation); as $s)

                    <input class="form-check-input" type="checkbox" id="{{$s}}" name="physical_limitation[]" value="{{$s}}" checked/>
                    <label class="form-check-label" for="{{$s}}">{{$s}}</label>
                    @endforeach
                    <hr style="width:50%">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="none" name="physical_limitation[]" value="none"  />
                        <label class="form-check-label" for="none">none</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="back pain" name="physical_limitation[]" value="back pain" />
                        <label class="form-check-label" for="back pain">back pain</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="knee pain" name="physical_limitation[]" value="knee pain" />
                        <label class="form-check-label" for="knee pain">knee pain</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="limited mobility" name="physical_limitation[]" value="limited mobility" />
                        <label class="form-check-label" for="limited mobility">limited mobility</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="other" name="physical_limitation[]" value="other" />
                        <label class="form-check-label" for="other">other</label>
                    </div>
                    @error('physical_limitation')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <br>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{url()->previous()}}" class="btn btn-secondary" >Cancel</a>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
    $('#weight,#height').on('change keyup input', (function() {
        const edit_w=$('#weight').val();
        const edit_h=$('#height').val();
        document.getElementById('bmi').value= parseFloat(edit_w/((edit_h)*(edit_h))*703).toFixed(1);

    })
    );
    $('#weight,#height,#neck,#waist,#age,#hip,#gender').on('change keyup input', (function() {
        const gender=$('#gender').children("option:selected").val();
        const weight=$('#weight').val();
        const height=$('#height').val();
        const neck=$('#neck').val();
        const waist=$('#waist').val();
        const age=$('#age').val();
        const hip=$('#hip').val();

        if(gender==="male"){
            console.log("male");
            document.getElementById('bfp').value= Math.round((86.010*(Math.log(waist*1-neck*1)/Math.log(10))-70.041*(Math.log(height)/Math.log(10))+36.76*1)*100)/100;

            document.getElementById('bmr').value = Math.round((parseInt(weight)*4.536) + (parseInt(height)*15.88) + - (parseInt(age)*5) + 5);

        }else{
            console.log("female");
            document.getElementById('bfp').value= Math.round((163.205*(Math.log(waist*1.0+hip*1.0-neck*1.0)/Math.log(10))- 97.684*(Math.log(height)/Math.log(10))-78.387*1.0)*100)/100;
            document.getElementById('bmr').value =Math.round((parseInt(weight)*4.536) + (parseInt(height)*15.88) + - (parseInt(age)*5) - 161);
        }

        })
        )

    })
</script>
@endpush
