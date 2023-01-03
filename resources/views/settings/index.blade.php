@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">User status</h1>
     </div>
    <div class="row">
          <div class="card col-12 shadow mb-4">
                <div class="card-header py-3">All status</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Points</th>
                          <th>Edit</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($statuses as $status)
                        <tr>
                          <td>{{$status->name}}</td>
                          <td>{{$status->points}}</td>
                          <td><a data-toggle="modal"
                            data-target="#update-modal" data-id="{{$status->id}}"
                            href="#"><i class="fas fa-pencil-alt"></i></a></td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>  
</div>



    <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Achievements</h1>
     </div>
    <div class="row">
          <div class="card col-12 shadow mb-4">
                <div class="card-header py-3">All achievements</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Title</th>
                          <th>Description</th>
                          <th>Points</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($achievements as $achievement)
                        <tr>
                          <td>{{$achievement->title}}</td>
                          <td>{{$achievement->description}}</td>
                          <td>{{$achievement->points}}</td>
                          
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>  
</div>

@include('settings.modal.update')
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('js/settings.js')}}"></script>