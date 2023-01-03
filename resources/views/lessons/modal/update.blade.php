@extends('layouts.modal.update.normal')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/modal.css')}}">
@endpush

@section('modal_form')
    <form method="POST" action="{{ route('lesson') }}" enctype="multipart/form-data">
        <div class="modal-body">
            @method('put')
            @csrf
            <input type="hidden" name="lesson_id" id="lesson_id"/>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger" style="display:none"></div>
                </div>
            </div>
            <div class="form-group ">
                <label for="name_update"
                       class="col-form-label text-md-right">{{ __('Name') }}</label>
                <input id="name_update" type="text" placeholder="{{__('Name')}}"
                       class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                       name="name" maxlength="45" value="{{ old('name') }}" required autofocus>
                @if ($errors->has('name'))
                    <span class="invalid-feedback"
                          role="alert"><strong>{{ $errors->first('name') }}</strong></span>
                @endif
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
            <button type="submit" class="btn btn-primary ld-ext-left">{{__('Update')}}
                <div class="ld ld-ring ld-spin"></div>
            </button>
        </div>
    </form>
@endsection
