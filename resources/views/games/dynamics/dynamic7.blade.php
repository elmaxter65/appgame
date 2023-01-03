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
                    <th>Topic</th>
                    <th>Lesson</th>
                    <th>Level</th>
                    <th>Dynamic</th>
                    <th>Points</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{$game->getTopicName($game->topics_id)}}</td>
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
              <div class="form-group col-12">
                  <label for="enterTitle">Title</label>
                  <input name="title" id="enterTitle" class="form-control" type="text" placeholder="Enter the title of the game (or leave empty)">
                </div>
              <div class="form-group col-12">
                  <label for="enterQuestion">Question</label>
                  <input required name="question" id="enterQuestion" class="form-control" type="text" placeholder="Enter the question of the game">
                </div>
                
                <div class="form-group col-12" id="answerDiv">
                  <label>Enter the possible answers and select the right ones</label>
                  <div class="form-row">

                      <div class="custom-file form-group col-8">
                          <label class="custom-file-label">Choose first image</label>
                          <input required name="image1" type="file" class="custom-file-input" id="chooseImage1" required>
                        </div>
                        <div class="col-4">
                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" name="correct_answer1" id="defaultCheck1">
                            <label class="form-check-label" for="defaultCheck1">Correct answer</label>
                          </div>
                      </div>
                  </div>
                </div>
                <button class="btn btn-primary" id="addnew" onclick="addNewAnswer()">Add another answer</button>

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

  <div class="form-row" style="display:none" id="newAnswerDiv">
        <div class="custom-file form-group col-8">
            <label class="custom-file-label">Choose first image</label>
            <input required name="image1" type="file" class="custom-file-input input-file" id="chooseImage1" required>
          </div>
          <div class="col-4">
                <div class="form-check">
                <input class="form-check-input input-checkbox" type="checkbox" value="1" name="correct_answer">
                <label class="form-check-label">Correct answer</label>
              </div>
          </div>
    </div>

@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<script type="text/javascript">

var count = 1;
function addNewAnswer()
{

  count = count +1;
  var div1 = document.createElement('div');
  div1.className = "form-row";
	// Get template data
	div1.innerHTML = document.getElementById('newAnswerDiv').innerHTML;
	// append to our form, so that template data
	//become part of form
	document.getElementById('answerDiv').appendChild(div1);

  div1.querySelector('.input-file').name = 'image'+count;
  div1.querySelector('.input-checkbox').name = 'correct_answer'+count;


}

</script>
