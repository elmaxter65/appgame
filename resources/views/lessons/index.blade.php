@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Lessons</h1>
     </div>
    <div class="row">
      <div class="card col-12 mb-4">
            <div class="card-header">Create a new lesson</div>
            <div class="card-body">
                    <form method="POST" id="formLesson" action="{{ route('lesson') }}">
                            @csrf 
                            <div class="form-group">
                              <label>Lesson name</label>
                              <input type="text" class="form-control" id="topicName" maxlength="45" name="name" placeholder="Enter the name of the lesson">
                            </div>
                            <div class="form-group">
                              <label for="selectTopic">Select related topic</label>
                              <select class="form-control" id="selectTopic" name="topic_id" required>
                                      @foreach($topics as $topic)
                                              <option value="{{$topic->id}}">{{$topic->name}}</option>
                                      @endforeach
                              </select>
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
                <div class="card-header py-3">All created lessons</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>@sortablelink('name')</th>
                          <th>Points</th>
                          <th>Related topic</th>
                          <th>Edit</th>
                          <th>Delete <button type="button" class="btn-info btn-warning" data-toggle="tooltip" data-placement="top" title="You can only delete a lesson if it doesn't have any games">
                            i
                          </button></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($lessons as $lesson)
                        <tr>
                          <td>{{$lesson->name}}</td>
                          <td>{{$lesson->points}}</td>
                          <td>{{$lesson->topic->name}}</td>
                          <td><a data-toggle="modal"
                            data-target="#update-modal" data-id="{{ $lesson->id }}"
                            href="#"><i class="fas fa-pencil-alt"></i></a></td>
                          <td><a class="remove-lesson" data-id="{{ $lesson->id }}"
                              href="#"><i class="fas fa-trash-alt"></i></a></td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                    {{ $lessons->links() }}
                  </div>
                </div>
              </div>


   
</div>

@include('lessons.modal.update')
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('js/lesson.js')}}"></script>