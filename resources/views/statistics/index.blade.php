@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Statistics</h1>
     </div>
    <div class="row">
      <div class="card col-12 mb-4">
          <div class="card-header">Download Excel sheets (zip file)</div>
            <div class="card-body">
                    <form method="POST" id="downloadExcel" action="{{ route('dowload.excel') }}">
                            @csrf 
                            <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" name="excel_password" required>
                            @error('excel_password')
                              <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Download Excel</button>
                            
                          </form>
           </div>
        </div>

    </div>


    <div class="row">
      <div class="card col-12 mb-4">
          <div class="card-header">Send Excel by email</div>
            <div class="card-body">
                    <form method="POST" id="emailExcel" action="{{ route('email.excel') }}">
                            @csrf 
                            <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email_excel" required>
                            @error('email_excel')
                              <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            </div>

                            <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" name="excel_send_password" required>
                            @error('excel_send_password')
                              <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Send Excel</button>

                            @if (Session::has('msg_ko'))
                            <div class="form-group">
                            <div class="alert alert-danger">{{ Session::get('msg_ko') }}</div>
                            </div>
                            @endif

                            @if (Session::has('msg_ok'))
                            <div class="form-group">
                            <div class="alert alert-success">{{ Session::get('msg_ok') }}</div>
                            </div>
                            @endif
                          </form>
           </div>
        </div>

    </div>

@endsection

