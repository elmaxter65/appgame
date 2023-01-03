@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Change your password</h1>
     </div>
    <div class="row">
      <div class="card col-12 mb-4">
            <div class="card-body">
                    <form method="POST" id="formNotification" action="{{ route('update.pass') }}">
                            @csrf 
                            <div class="form-group">
                              <label>Current password</label>
                              <input type="password" class="form-control" name="current_password">
                              @if($errors->has('current_password'))
                              <span class="d-block invalid-feedback" role="alert">
                                <strong>{{ $errors->first('current_password') }}</strong>
                            </span>
                                @endif
                            </div>
                            
                            <div class="form-group">
                              <label>New password(*)</label>
                              <input type="password" class="form-control" name="new_password">
                              <span style="font-size:12px">*Password need to have at least 1 uppercase, 1 number and 1 special character and a minumum of 8 digits</span>
                              @if($errors->has('new_password'))
                              <span class="d-block invalid-feedback" role="alert">
                                <strong>{{ $errors->first('new_password') }}</strong>
                            </span>
                                @endif
                            </div>
                            <div class="form-group">
                              <label>Repeat new password</label>
                              <input type="password" class="form-control" name="new_password_confirmation">
                              @if($errors->has('new_password_confirmation'))
                              <span class="d-block invalid-feedback" role="alert">
                                <strong>{{ $errors->first('new_password_confirmation') }}</strong>
                            </span> 
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">Change</button>

                            @if(isset($msg))
                              <div style="width:100%;margin-top:20px" class="alert alert-{{ $type_msg }}">
                              <p>{{ $msg }}</p>
                              </div>
                            @endif
                          </form>
           </div>
        </div>

    </div>

@endsection

