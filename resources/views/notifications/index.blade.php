@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Notifications</h1>
     </div>
    <div class="row">
      <div class="card col-12 mb-4">
            <div class="card-header">Create a new notification</div>
            <div class="card-body">
                    <form method="POST" id="formNotification" action="{{ route('notification') }}">
                            @csrf 
                            <input type="hidden" id="sendNotification" value={{route('notification.send')}}>
                            <div class="form-group">
                              <label>Notification title</label>
                              <input type="text" class="form-control" id="title" name="title">
                            </div>
                            <div class="form-group">
                              <label>Notification content</label>
                              <textarea class="form-control" id="notification" name="notification"></textarea>
                            </div>
                            @if ($errors->any())
                              @foreach ($errors->all() as $error)
                              
                                <div class="alert alert-danger">{{$error}}</div>
                              
                              @endforeach
                                @endif
                            <button type="submit" class="btn btn-primary">Create</button>
                            @if(isset($msg))
                              <div style="width:100%;margin-top:20px" class="alert alert-{{ $type_msg }}">
                              <p>{{ $msg }}</p>
                              </div>
                              @endif
                          </form>
           </div>
        </div>

    </div>
    <div class="row">
          <div class="card col-12 shadow mb-4">
                <div class="card-header py-3">All notifications</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Title</th>
                          <th>Notification</th>
                          <th>Send</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($notifications as $notification)
                        <tr>
                          <td>{{$notification->title}}</td>
                          <td>{{$notification->notification}}</td>
                          @if($notification->send == 0)
                          <td><a class="send-notification" data-id="{{ $notification->id }}"
                            href="#"><i class="fas fa-share"></i></a></td>
                            @else
                            <td>Sent</td>
                            @endif
                          <td><a class="remove-notification" data-id="{{ $notification->id }}"
                              href="#"><i class="fas fa-trash-alt"></i></a></td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>


   
</div>

@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('js/notification.js')}}"></script>