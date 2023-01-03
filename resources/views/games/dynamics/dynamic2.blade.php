@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Games</h1>
     </div>

     <div class="row">
      <div class="card col-12 shadow mb-4">
          <div class="card-header py-3">Add content to the selected game</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>Lesson</th>
                    <th>Level</th>
                    <th>Dynamic</th>
                    <th>Points</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{$game->title}}</td>
                    <td>{{$game->getLessonName($game->lesson_id)}}</td>
                    <td>{{$game->level}}</td>
                    <td>{{$game->dynamic_number}}</td>
                    <td>{{$game->points}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <form method="POST" action="{{ route('game.add.content') }}" class="row" enctype="multipart/form-data">
              @csrf 
              <input type='hidden' name="dynamic_number" value="{{$game->dynamic_number}}">
              <input type='hidden' name="game_id" value="{{$game->id}}">
              <label>Define the content of this game</label>
                <div class="custom-file form-group col-12">
                  <label >Choose image (jpg, png or jpeg) or video (mp4)</label>
                  <input required name="image" type="file" id="chooseImage1" required>
                </div>
                @error('image')
                  <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <div class="form-group form-inline col-12">
                <legend class="col-form-label pt-0">What is the answer?</legend>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="correct_answer" id="answer1" value="1" checked>
                    <label class="form-check-label" for="answer1">
                      Yes
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="correct_answer" id="answer0" value="0">
                    <label class="form-check-label" for="answer0">
                      No
                    </label>
                  </div>
                </div>
                <div class="form-group col-12">
                  <label for="feedbackOk">Feedback for correct answer</label>
                  <textarea name="feedback_ok" id="feedbackOk" class="form-control"></textarea>
                </div>
                <div class="form-group col-12">
                  <label for="feedbackKo">Feedback for wrong answer</label>
                  <textarea name="feedback_ko" id="feedbackKo" class="form-control"></textarea>
                </div>
                
              <button type="submit" class="btn btn-primary">Add</button>
            </form>
            <div class="alert alert-danger">
              <p>Before you add this content, make sure it is correct. After saving it if you want to change it, you'll need to delete the game and create it again.</p>
          </div>

          </div>
        </div>

  </div>



@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
