@extends('layouts.modal.update.normal')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/modal.css')}}">
@endpush

@section('modal_form')
    <form method="POST" action="{{ route('game') }}" enctype="multipart/form-data">
        <div class="modal-body">
            @method('put')
            @csrf
            <input type="hidden" name="game_id" id="game_id"/>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger" style="display:none"></div>
                </div>
            </div>
            <div class="form-group ">
                <p>The only thing you can change once you have defined a game is the points. If you wan to change the rest of its configuration, please delete it and create a new one.</p>
                <label for="points_update"
                       class="col-form-label text-md-right">{{ __('Points') }}</label>
                <input id="points_update" type="text" placeholder="{{__('Points')}}"
                       class="form-control{{ $errors->has('points') ? ' is-invalid' : '' }}"
                       name="points" value="{{ old('points') }}" required autofocus>
                @if ($errors->has('points'))
                    <span class="invalid-feedback"
                          role="alert"><strong>{{ $errors->first('points') }}</strong></span>
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
