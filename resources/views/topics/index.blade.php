@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Topics</h1>
     </div>
    <div class="row">
      <div class="card col-12 mb-4">
            <div class="card-header">Create a new topic</div>
            <div class="card-body">
                    <form method="POST" id="formTopic" action="{{ route('topic') }}">
                            @csrf 
                            <div class="form-group col-10">
                              <label>Topic name</label>
                              <input type="text" class="form-control" id="topicName" maxlength="40" name="name" placeholder="Enter the name of the topic">
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
                <div class="card-header py-3">All created topics</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>@sortablelink('name')</th>
                          <th>Points <button type="button" class="btn-info btn-secondary" data-toggle="tooltip" data-placement="top" title="These points are the addition of all the games' points of all the topic's lessons">
                            i
                          </button></th>
                          <th>Edit</th>
                          <th>Delete <button type="button" class="btn-info btn-secondary" data-toggle="tooltip" data-placement="top" title="You can only delete a topic if it doesn't have any lessons, content or games">
                              i
                            </button></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($topics as $topic)
                        <tr>
                          <td>{{$topic->name}}</td>
                          <td>{{$topic->points}}</td>
                          <td><a data-toggle="modal"
                            data-target="#update-modal" data-id="{{ $topic->id }}"
                            href="#"><i class="fas fa-pencil-alt"></i></a></td>
                          <td><a class="remove-topic" data-id="{{ $topic->id }}"
                              href="#"><i class="fas fa-trash-alt"></i></a></td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                    {{ $topics->links() }}
                  </div>
                </div>
              </div>


   
</div>

@include('topics.modal.update')
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('js/topic.js')}}"></script>