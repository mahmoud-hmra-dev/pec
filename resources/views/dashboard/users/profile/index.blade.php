@extends('dashboard.layout.main')
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{$message}}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="content pt-5 pb-5">
        <div class="container-fluid">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-8 ">
                    <div class="card">
                        <div class="card-header card-header-icon card-header-rose">
                            <h4 class="card-title">{{ __('Edit Profile') }}
                            </h4>
                        </div>
                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data" action="{{ route('profile.update',auth()->user()->id) }}" autocomplete="off" class="form-horizontal">
                                @csrf
                                @method('put')
                                <div class="row" id="data-form">
                                    <div class="form-group col-md-6">
                                        <label class="required-label">First Name</label>
                                        <input type="text" name="first_name" class="form-control" value="{{old('first_name',auth()->user()->first_name)}}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="required-label">Last Name</label>
                                        <input type="text" name="last_name" class="form-control" value="{{old('last_name',auth()->user()->last_name)}}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="required-label">Email</label>
                                        <input type="email" name="email" class="form-control" required value="{{old('email',auth()->user()->email)}}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Personal Email</label>
                                        <input type="email" name="personal_email" class="form-control" value="{{old('personal_email',auth()->user()->personal_email)}}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Phone</label>
                                        <input type="text" name="phone" class="form-control" value="{{old('personal_email',auth()->user()->phone)}}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Country</label>
                                        <select class="form-control" id="country_id" name="country_id" >
                                            @foreach($countries as $country)
                                                <option value="{{$country->id}}"
                                                        data-phone="{{$country->phone_extension}}">{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="city">City</label>
                                            <input name="city" type="text" class="form-control" value="{{old('personal_email',auth()->user()->city)}}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input name="address" type="text" class="form-control" value="{{old('personal_email',auth()->user()->address)}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-2 text-right">
                                    <button type="submit" class="edit btn btn-xs btn-primary" >
                                        Submit
                                    </button>
                                </div>
                            </form>
                            <form method="post" class="mt-3 text-right" action="{{ route('profile.destroy', auth()->id()) }}" onsubmit="return confirm('Are you sure you want to permanently delete your profile?');">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-xs btn-outline-danger">
                                    Delete profile
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header card-header-icon card-header-rose">
                            <h4 class="card-title">{{ __('Change password') }}</h4>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('profile.password',auth()->user()->id) }}" class="form-horizontal">
                                @csrf
                                @method('put')
                                <div class="row" id="data-form">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="current_password" class="label-required">Current Password</label>
                                            <input name="current_password" type="password" class="form-control" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="password" class="label-required">New Password</label>
                                            <input name="password" type="password" class="form-control" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="password_confirmation" class="label-required">Confirm New Password</label>
                                            <input name="password_confirmation" type="password" class="form-control" value="" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group m-2 float-right">
                                    <button type="submit" class="edit btn btn-xs btn-primary mr-2" >
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <script>

    </script>
@endpush
