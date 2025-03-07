@extends('layouts.app')

@section('content')
    <div class="container-fluid my-md-0 my-4">
        <div class="row h-100vh">
            <div class="col-lg-8 col-md-10 m-auto">
                <div class="card rounded-12 border-0 shadow">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                        <h2 class="m-0">{{ __('Edit_Customer') }}</h2>
                        <a class="btn btn-danger" href="{{ route('customer.index') }}"> {{ __('Back') }} </a>
                    </div>
                    <div class="card-body">
                        <form @can('customer.update') action="{{ route('customer.update', $customer->id) }}" @endcan
                            method="POST" enctype="multipart/form-data"> @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-12 col-md-6 mb-2">
                                    <label for="">{{ __('First_Name') }} <strong class="text-danger">*</strong></label>
                                    <input type="text" class="form-control" name="first_name"
                                        value="{{ old('first_name') ?? $customer->user->first_name }}"
                                        placeholder="First name">
                                    @error('first_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-6 mb-2">
                                    <label for="">{{ __('Last_Name') }} <strong class="text-danger">*</strong></label>
                                    <input type="text" class="form-control" name="last_name"
                                        value="{{ old('last_name') ?? $customer->user->last_name }}"
                                        placeholder="Last name">
                                    @error('last_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-6 mb-2">

                                <label for="mobile">{{ __('Mobile_number') }} <strong class="text-danger">*</strong></label>
                                <input 
                                    type="text" 
                                    id="mobile" 
                                    class="form-control" 
                                    name="mobile" 
                                    value="{{ old('mobile') }}" 
                                    placeholder="Enter your mobile number" 
                                    maxlength="10" 
                                    pattern="\d{10}" 
                                    required
                                    title="Mobile number must be exactly 10 digits.">
                                @error('mobile')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <input type="hidden" name="type" value="mobile">
                            </div>

                                <div class="col-12 col-md-6 mb-2">
                                    <label for="">{{ __('Email') }}</label>
                                    <input type="text" class="form-control" name="email"
                                        value="{{ old('email') ?? $customer->user->email }}" placeholder="Email">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                @if (request()->routeIs('customer.create'))
                                    <div class="col-12 col-md-6 mb-2">
                                        <label for="">{{ __('Password') }} <strong class="text-danger">*</strong></label>
                                        <input type="password" class="form-control" name="password" placeholder="******">
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6 mb-2">
                                        <label for="">{{ __('Confirm_Password') }}</label>
                                        <input type="password" class="form-control" name="password_confirmation"
                                            placeholder="******">
                                    </div>
                                @endif
                                <div class="col-12 col-md-6 mb-2 py-2">
                                    <label for="">{{ __('Customer_Photo') }}</label>
                                    <input type="file" class="form-control-file" name="profile_photo">
                                </div>
                                @can('customer.update')
                                    <div class="col-12 col-md-6 mb-2 py-2">
                                        <label for=""></label>
                                        <button class="btn btn-primary w-100 mt-2">{{ __('Submit') }}</button>
                                    </div>
                                @endcan
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
