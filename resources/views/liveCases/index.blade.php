@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Live Cases</h1>
     </div>
    <div class="row">
      <div class="card col-12 mb-4">
            <div class="card-header">Create a new live case</div>
            <form method="POST" id="formLiveCase" action="{{ route('live.case') }}" enctype="multipart/form-data">
            <div class="card-body">
                            @csrf 
                            <div class="form-group row">
                              <div class="col-md-8">
                                <label>Title</label>
                                <input required type="text" class="form-control" id="title" name="title" placeholder="Enter the title of the live case">
                              </div>
                              <div class="col-md-4">
                                <label for="points">Points</label>
                                  <input required class="form-control" type="number" name="points" id="points" placeholder="Enter the number of points this live case will give">
                                </div>
                            </div>
                            <div class="form-group">
                              <label for="selectTopic">Select related topic</label>
                              <select class="form-control" id="selectTopic" name="topic_id" required>
                                      @foreach($topics as $topic)
                                              <option value="{{$topic->id}}">{{$topic->name}}</option>
                                      @endforeach
                              </select>
                              </div>
                            
                            <div class="row">
                            <div class="form-group col-md-3">
                              <label>Patient Name</label>
                              <input required type="text" class="form-control" id="patient_name" name="patient_name" placeholder="Enter the name of the patient">
                            </div>
                            <div class="form-group col-md-3">
                              <label>Patient Age</label>
                              <input required type="number" class="form-control" id="patient_age" name="patient_age" placeholder="Enter the age of the patient">
                            </div>
                            <div class="form-group col-md-3">
                              <label>Patient Sex</label>
                              <input required type="text" class="form-control" id="patient_sex" name="patient_sex" placeholder="Enter male or female">
                            </div>
                            <div class="form-group col-md-2">
                              <label for="selectLesson">Difficulty level</label>
                              <select class="form-control" id="difficulty_level" name="difficulty_level" required>
                                  <option value="0">0</option>
                                  <option value="0.5">0.5</option>
                                  <option value="1">1</option>
                                  <option value="1.5">1.5</option>
                                  <option value="2">2</option>
                                  <option value="2.5">2.5</option>
                                  <option value="3">3</option>
                                  <option value="3.5">3.5</option>
                                  <option value="4">4</option>
                                  <option value="4.5">4.5</option>
                                  <option value="5">5</option>
                                      
                              </select>
                              </div>
                            </div>
                            <div class="form-group col-12">
                              <label for="med_history">Medical history</label>
                              <textarea required name="med_history" id="med_history" class="form-control"></textarea>
                            </div>
                            <div class="form-group col-12">
                              <label for="med_history">Symptoms</label>
                              <textarea required name="symptoms" id="symptoms" class="form-control"></textarea>
                            </div>

                            <div class="form-group col-12">
                              <label class="custom-file-label">Choose image case file</label>
                              <input required name="main_img" type="file" class="custom-file-input" id="chooseImg">
                            </div>
                        </div>
                        <div class="card-header">Equipment</div>
                        <div class="card-body">
                            
                            <div class="form-group col-md-12">
                              <label>Equipment header</label>
                              <input required type="text" class="form-control" id="equipment_header" name="equipment_header" placeholder="Enter the text describing the equipment">
                            </div>

                            <label>Equipment options</label>
                           <div class="form-group col-md-12">
                            <div class="row">
                              <div class="form-group col-6">
                                <label class="custom-file-label">Choose first image equipment</label>
                                <input required name="equipment_img_1" type="file" class="custom-file-input" id="chooseImg">
                                </div>
                              <div class="form-group col-6">
                                
                                <input class="form-control" type="number" name="equipment_percentage_1" placeholder="Enter the percentage this answer gives">
                              </div>
                            
                            </div>
                           </div>
                          
                           <div class="form-group col-md-12">
                          <div class="row">
                            <div class="form-group col-6">
                              <label class="custom-file-label">Choose second image equipment</label>
                              <input required name="equipment_img_2" type="file" class="custom-file-input" id="chooseImg">
                              </div>
                            <div class="form-group col-6">
                              
                              <input class="form-control" type="number" name="equipment_percentage_2" placeholder="Enter the percentage this answer gives">
                            </div>
                          
                          </div>
                        </div>

                        <div class="form-group col-md-12">
                          <div class="row">
                            <div class="form-group col-6">
                              <label class="custom-file-label">Choose thrid image equipment</label>
                              <input required name="equipment_img_3" type="file" class="custom-file-input" id="chooseImg">
                              </div>
                            <div class="form-group col-6">
                              
                              <input class="form-control" type="number" name="equipment_percentage_3" placeholder="Enter the percentage this answer gives">
                            </div>
                          
                          </div>

                        </div>

                        <div class="form-group col-md-12">
                          <div class="row">
                            <div class="form-group col-6">
                              <label class="custom-file-label">Choose forth image equipment</label>
                              <input required name="equipment_img_4" type="file" class="custom-file-input" id="chooseImg">
                              </div>
                            <div class="form-group col-6">
                             
                              <input class="form-control" type="number" name="equipment_percentage_4" placeholder="Enter the percentage this answer gives">
                            </div>
                          </div>
                        </div>
                      </div>
                          <div class="card-header">Exploration</div>
                          <div class="card-body">
                          <div class="form-group col-md-12">
                            <label>Exploration header</label>
                            <input required type="text" class="form-control" id="exploration_header" name="exploration_header" placeholder="Enter the text describing the exploration">
                          </div>
                          <label>Upload the images for the exploration</label>
                          <div class="form-group col-12" id="answerDivImage">
                            <div class="form-row form-group">
                              <div class="col-6">
                                <label class="custom-file-label">Choose exploration image</label>
                                <input required name="image1-1" type="file" class="custom-file-input" id="chooseImage1" required>
                            </div>
                              <div class="col-6">
                                <label class="custom-file-label">Choose transversal image</label>
                                <input required name="image2-1" type="file" class="custom-file-input" id="chooseImage1" required>
                            </div>
                            </div>
                          </div>
                          <button class="btn btn-primary" id="addnew" onclick="addNewAnswerImage()">Add another set of images</button>
                        </div>
                        <div class="card-header">Exploration answers</div>
                        <div class="card-body">
                            <div class="form-group col-md-12">
                                <label>Exploration answers header</label>
                                <input required type="text" class="form-control" id="exploration_answers_header" name="exploration_answers_header" placeholder="Enter the text describing the exploration answers">
                              </div>
                              <label>Define the answers</label>
                          <div class="form-group col-12" id="answerDiv">
                              <div class="form-row form-group">
                                <div class="col-8">
                                <input required name="exploration_answer_1" id="enterAnswer" class="form-control" type="text" placeholder="Enter the possible answer">
                              </div>
                                <div class="col-4">
                                    <input class="form-control" type="number" name="exploration_percentage_1" placeholder="Enter the percentage this answer gives">
                                </div>
                              </div>
                            </div>
                            <button class="btn btn-primary" id="addnew" onclick="addNewAnswer()">Add another answer</button>

                        </div>
                        <div class="card-header">Treatment</div>
                        <div class="card-body">
                            <div class="form-group col-md-12">
                                <label>Treatment header</label>
                                <input required type="text" class="form-control" id="treatment_header" name="treatment_header" placeholder="Enter the text describing the treatments">
                              </div>
                              <label>Define the possible treatments answers</label>
                              <div class="form-group col-12" id="answerDivTreatment">
                                  <div class="form-row form-group">
                                    <div class="col-8">
                                    <input required name="treatment_answer_1" id="enterAnswer" class="form-control" type="text" placeholder="Enter the possible answer">
                                  </div>
                                    <div class="col-4">
                                        <input class="form-control" type="number" name="treatment_percentage_1" placeholder="Enter the percentage this answer gives">
                                    </div>
                                  </div>
                                </div>
                                <button class="btn btn-primary" id="addnewtreatment" onclick="addNewAnswerTreatment()">Add another answer</button>


                        </div>
                        <div class="card-header">Treatment - stent</div> 
                        <div class="card-body">
                            <div class="form-group col-md-12">
                                <label>Treatment header</label>
                                <input required type="text" class="form-control" id="stent_header" name="stent_header" placeholder="Enter the text describing the stents">
                              </div>
                            <label>Define the possible stents answers</label>
                            <div class="form-group col-12" id="answerDivStent">
                                <div class="form-row form-group">
                                  <div class="col-8">
                                  <input required name="stent_answer_1" id="enterAnswer" class="form-control" type="text" placeholder="Enter the possible answer">
                                </div>
                                  <div class="col-4">
                                      <input class="form-control" type="number" name="stent_percentage_1" placeholder="Enter the percentage this answer gives">
                                  </div>
                                </div>
                              </div>
                              <button class="btn btn-primary" id="addnewstent" onclick="addNewAnswerStent()">Add another answer</button>
                             
                            <button type="submit" class="btn btn-primary" style="display:block;margin-top:15px">Create</button>
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
                <div class="card-header py-3">All created live cases</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Title</th>
                          <th>Patient Name</th>
                          <th>Difficulty Level</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($livecases as $livecase)
                        <tr>
                          <td>{{$livecase->title}}</td>
                          <td>{{$livecase->patient_name}}</td>
                          <td>{{$livecase->difficulty_level}}</td>
                          <td><a data-toggle="modal"
                            data-target="#update-modal" data-id="{{ $livecase->id }}"
                            href="#"><i class="fas fa-pencil-alt"></i></a></td>
                          <td><a class="remove-livecase" data-id="{{ $livecase->id }}"
                              href="#"><i class="fas fa-trash-alt"></i></a></td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>


   
</div>



    <div class="form-row" style="display:none" id="newAnswerDiv">
      <div class="col-8">
      <input required name="exploration_answer_0" id="enterAnswer" class="form-control input-text" type="text" placeholder="Enter the possible answer">
    </div>
      <div class="col-4">
          <input class="form-control input-percentage" type="number" name="equipment_percentage" placeholder="Enter the percentage this answer gives">
      </div>
    </div>


    
      
      <div class="form-row" style="display:none" id="newAnswerDivImage">
        <div class="col-6">
          <label class="custom-file-label">Choose exploration image</label>
          <input required name="image1" type="file" class="custom-file-input input-file-1" id="chooseImage1" required>
      </div>
        <div class="col-6">
          <label class="custom-file-label">Choose transversal image</label>
          <input required name="image2" type="file" class="custom-file-input input-file-2" id="chooseImage2" required>
      </div>
      </div>

      <div class="form-row form-group" style="display:none" id="newAnswerDivTreatment">
          <div class="col-8">
          <input required name="treatment_answer_0" id="enterAnswer" class="form-control input-text" type="text" placeholder="Enter the possible answer">
        </div>
          <div class="col-4">
              <input class="form-control input-percentage" type="number" name="treatment_percentage" placeholder="Enter the percentage this answer gives">
          </div>
        </div>

      <div class="form-row form-group" style="display:none" id="newAnswerDivStent">
          <div class="col-8">
          <input required name="stent_answer_0" id="enterAnswer" class="form-control input-text" type="text" placeholder="Enter the possible answer">
        </div>
          <div class="col-4">
              <input class="form-control input-percentage" type="number" name="stent_percentage" placeholder="Enter the percentage this answer gives">
          </div>
        </div>
    


@include('liveCases.modal.update')
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('js/liveCase.js')}}"></script>

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
  
    div1.querySelector('.input-text').name = 'exploration_answer_'+count;
    div1.querySelector('.input-percentage').name = 'exploration_percentage_'+count;
  
  
  }
  var count_2 = 1;
  function addNewAnswerImage()
  {
  
    count_2 = count_2 +1;
    var div1 = document.createElement('div');
    div1.className = "form-row";
    // Get template data
    div1.innerHTML = document.getElementById('newAnswerDivImage').innerHTML;
    // append to our form, so that template data
    //become part of form
    document.getElementById('answerDivImage').appendChild(div1);
  
    div1.querySelector('.input-file-1').name = 'image1-'+count_2;
    div1.querySelector('.input-file-2').name = 'image2-'+count_2;
    
  
  
  }

  var count_3 = 1;
  function addNewAnswerTreatment()
  {
  
    count_3 = count_3 +1;
    var div1 = document.createElement('div');
    div1.className = "form-row";
    // Get template data
    div1.innerHTML = document.getElementById('newAnswerDivTreatment').innerHTML;
    // append to our form, so that template data
    //become part of form
    document.getElementById('answerDivTreatment').appendChild(div1);
  
    div1.querySelector('.input-text').name = 'treatment_answer_'+count_3;
    div1.querySelector('.input-percentage').name = 'treatment_percentage_'+count_3;
  
  
  }
  var count_4 = 1;
  function addNewAnswerStent()
  {
  
    count_4 = count_4 +1;
    var div1 = document.createElement('div');
    div1.className = "form-row";
    // Get template data
    div1.innerHTML = document.getElementById('newAnswerDivStent').innerHTML;
    // append to our form, so that template data
    //become part of form
    document.getElementById('answerDivStent').appendChild(div1);
  
    div1.querySelector('.input-text').name = 'stent_answer_'+count_4;
    div1.querySelector('.input-percentage').name = 'stent_percentage_'+count_4;
  
  
  }
  
  </script>