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
                <div class="form-group col-12" id="newDiv">
                  <div class="form-row input-container" id="1">
                        
                          <div class="form-group col-12">
                                <label for="enterTitle">Text before select</label>
                                <textarea required name="textBefore1" id="enterTextBefore" class="form-control"></textarea>
                          </div>

                          <div class="form-group col-12" id="answerDiv1" style="padding-left:35px">
                              <label>Enter the possible answers and select the right ones</label>
                              <div class="form-row">
                                <div class="col-8">
                                <input required name="answer1-1" id="enterAnswer" class="form-control enterAnswer" type="text" placeholder="Enter the possible answer">
                              </div>
                                <div class="col-4">
                                      <div class="form-check">
                                      <input class="form-check-input" type="checkbox" value="1" name="correct_answer1-1" id="defaultCheck1">
                                      <label class="form-check-label" for="defaultCheck1">Correct answer</label>
                                    </div>
                                </div>
                              </div>
                            </div>
                            <button style="margin-left:35px;margin-bottom:35px" class="btn btn-primary" id="button1" onclick="addNewAnswer(this)">Add another answer</button>

                          <div class="form-group col-12">
                                <label for="enterTitle">Text after select</label>
                                <textarea required name="textAfter1" id="enterTextAfter" class="form-control"></textarea>
                          </div>
                  </div>
                </div>

                <button style="margin-bottom:50px" class="btn btn-primary" id="button1" onclick="addNewDiv()">Add another paragraph</button>
                

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
      <div class="col-8">
      <input required name="answer1" id="enterAnswer" class="form-control input-text enterAnswer" type="text" placeholder="Enter the possible answer">
    </div>
      <div class="col-4">
            <div class="form-check">
            <input class="form-check-input input-checkbox" type="checkbox" value="1" name="correct_answer1" id="defaultCheck1">
            <label class="form-check-label" for="defaultCheck1">Correct answer</label>
          </div>
      </div>
    </div>


              <div class="form-row input-container" style="display:none" id="newParagraphDiv">
                        <hr style="width:100%;border-top:5px solid rgba(0,0,0,.1);">
                          <div class="form-group col-12">
                                <label for="enterTitle">Text before select</label>
                                <textarea name="textBefore" id="enterTextBefore" class="form-control input-textBefore"></textarea>
                          </div>

                          <div class="form-group col-12" id="answerDiv1" style="padding-left:35px">
                              <label>Enter the possible answers and select the right ones</label>
                              <div class="form-row">
                                <div class="col-8">
                                <input required name="answer1" id="enterAnswer" class="form-control enterAnswer" type="text" placeholder="Enter the possible answer">
                              </div>
                                <div class="col-4">
                                      <div class="form-check">
                                      <input class="form-check-input" type="checkbox" value="1" name="correct_answer1" id="defaultCheck1">
                                      <label class="form-check-label" for="defaultCheck1">Correct answer</label>
                                    </div>
                                </div>
                              </div>
                            </div>
                            <button style="margin-left:35px;margin-bottom:35px" class="btn btn-primary input-button-add" id="button1" onclick="addNewAnswer(this)">Add another answer</button>

                          <div class="form-group col-12">
                                <label for="enterTitle">Text after select</label>
                                <textarea name="textAfter" id="enterTextBefore" class="form-control input-textAfter"></textarea>
                          </div>
                  </div>




@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<script type="text/javascript">

var count_par = 1;
// add a new paragraph
function addNewDiv()
{

count_par = count_par +1;
var div1 = document.createElement('div');
div1.className = "form-row";
div1.setAttribute('id',count_par);


// Get template data
div1.innerHTML = document.getElementById('newParagraphDiv').innerHTML;
// append to our form, so that template data
//become part of form
document.getElementById('newDiv').appendChild(div1);

var element1 = document.getElementById(count_par);
var element2 = element1.querySelector("#answerDiv1");
var element3 = element2.querySelector("#enterAnswer");
var element4 = element2.querySelector("#defaultCheck1");

element2.setAttribute('id','answerDiv'+count_par);
element3.name = 'answer'+count_par+'-1';
element4.name = 'correct_answer'+count_par+'-1';


div1.querySelector('.input-button-add').id = 'button'+count_par;
div1.querySelector('.input-textBefore').name = 'textBefore'+count_par;
div1.querySelector('.input-textAfter').name = 'textAfter'+count_par;



}

// add new answer
//var count = 1;
function addNewAnswer(elem)
{

var string_id = elem.id;
var id = string_id.slice(6, string_id.length);


//count = count +1;
var div1 = document.createElement('div');
div1.className = "form-row";
// Get template data
div1.innerHTML = document.getElementById('newAnswerDiv').innerHTML;
// append to our form, so that template data
//become part of form
document.getElementById('answerDiv'+id).appendChild(div1);

var count = $('#answerDiv'+id+ ' .enterAnswer').length;

if(id == 1){
  count = count -1;
}

div1.querySelector('.input-text').name = 'answer'+id+'-'+count;
div1.querySelector('.input-checkbox').name = 'correct_answer'+id+'-'+count;


}



</script>
