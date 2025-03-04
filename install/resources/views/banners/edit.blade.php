@extends('layouts.app')

@section('content')
<div class="container-fluid my-4">
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0 mt-2 mt-sm-0 d-flex">
            <a href="{{ route('banner.promotional') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-7 col-xxl-7 col-lg-7 m-auto">
            <div class="card shadow border-0 rounded-12">
                <div class="card-header">
                    <h2 class="card-title m-0">{{ __('Edit_Banner') }}</h2>
                </div>
                <div class="card-body">
                    <x-form type="Update" method="true" route="banner.update" updateId="{{ $banner->id }}">
                        <div class="form-group text-center">
                            <img width="50%" src="{{ $banner->thumbnailPath }}" alt="">
                        </div>
                        <label class="mb-1">{{ __('Title') }}</label>
                        <x-input name='title' type="text" placeholder="Banner title" value="{{ $banner->title }}"/>

                        <label class="mb-1">{{ __('Photo') }}</label>
                        <x-input-file name="image"/>

                        <label class="mb-1">{{ __('Description') }}</label>
                        <x-textarea name="description" placeholder="Banner description" value="{{ $banner->description }}" />

                        <div class="form-group">
                            <label for="active">
                                <input type="radio" id="active" name="active" {{ $banner->is_active ? 'checked' : '' }} value="1"> {{ __('Active') }}
                            </label>
                            <label for="in_active" class="ml-4">
                                <input type="radio" id="in_active" name="active" {{ !$banner->is_active ? 'checked' : '' }} value="0"> {{ __('Inactive') }}
                            </label>
                        </div>
                    </x-form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $('#name').keyup(function() {
            $('#slug').val($(this).val().toLowerCase().split(',').join('').replace(/\s/g,"-"));
		});
    </script>
@endpush
