@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">App Users</h1>
     </div>

     @if(isset($messageKo))
    <div class="alert alert-danger">{{$messageKo}}</div>
     @endif
    @if(isset($userSearch))
     <div class="row">
      <div class="card col-12 shadow mb-4">
        <div class="card-header py-3">Search result</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Hospital</th>
                  <th>State</th>
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody>
               
                <tr>
                  <td>@if($userSearch->name != null) {{decrypt($userSearch->name)}} @else -- @endif</td>
                  <td>{{decrypt($userSearch->email)}}</td>
                  <td>@if($userSearch->hospital != null) {{decrypt($userSearch->hospital)}} @else -- @endif</td>
                  <td>@if($userSearch->state) {{ 'Confirmed'}}  @else  {{ 'Not confirmed'}} @endif</td>
                  
                  <td><a class="remove-user" data-url="{{ route('user.app.delete')}}" data-id="{{ $userSearch->id }}"
                      href="#"><i class="fas fa-trash-alt"></i></a></td>
                </tr>
                
              </tbody>
              
            </table>
          </div>
        </div>
      </div>
     </div>
    @endif

    <div class="row">
      <div class="card col-12 shadow mb-4">
        <div class="card-header py-3">Find user</div>
        <div class="card-body">

        <form method="POST" action="{{ route('user.app.search')}}">
          @csrf
            <div class="form-group">
              <label>Email</label>
              <input type="email" class="form-control" name="email" placeholder="Enter the email" required>
            </div>
            <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Search">
          </div>
          </form>

        </div>
      </div>

    </div>

    <div class="row">
        <div class="card col-12 shadow mb-4">
            <div class="card-header py-3">All users</div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>@sortablelink('name')</th>
                      <th>@sortablelink('email')</th>
                      <th>Hospital</th>
                      <th>@sortablelink('state')</th>
                      
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($usersApp as $user)
                    <tr>
                      <td>@if($user->name != null) {{decrypt($user->name)}} @else -- @endif</td>
                      <td>{{decrypt($user->email)}}</td>
                      <td>@if($user->hospital != null) {{decrypt($user->hospital)}} @else -- @endif</td>
                      <td>@if($user->state) {{ 'Confirmed'}}  @else  {{ 'Not confirmed'}} @endif</td>
                      
                    <td><a class="remove-user" data-url="{{ route('user.app.delete')}}" data-id="{{ $user->id }}"
                          href="#"><i class="fas fa-trash-alt"></i></a></td>
                    </tr>
                    @endforeach
                  </tbody>
                  
                </table>
                {{ $usersApp->links() }}
              </div>
            </div>
          </div>

    </div>


@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('js/userApp.js')}}"></script>
