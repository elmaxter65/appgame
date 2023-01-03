@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Lesson's content</h1>
     </div>
    <div class="row">
      <div class="card col-12 mb-4">
            <div class="card-header">Add content to a lesson inside a topic</div>
            <div class="card-body">
                    <form method="POST" id="formTopicContent" action="{{ route('topic.content.create') }}">
                            @csrf 
                            <div class="form-group">
                            <label for="selectTopic">Select lesson and related topic</label>
                            <select class="form-control" id="selectTopic" name="lesson_id" required>
                                    @foreach($lessons as $lesson)
                                            <option value="{{$lesson->id}}" @if(in_array($lesson->id,$array_topic_content_id)) disabled @endif>{{$lesson->name."(".$lesson->getTopic($lesson->topic_id).")"}}</option>
                                    @endforeach
                            </select>
                            </div>
                            <div id="partDiv">
                            <div class="form-group">
                            <div class="form-group">
                                <label>Heading</label>
                                <input type="text" class="form-control" id="topicName" name="heading1" placeholder="Enter the heading of this part" required>
                              </div>
                            <div class="form-group">
                                <textarea name="content1" class="my-editor" id="editor1" rows="10" cols="80"></textarea>

                            </div>
                            
                          </div>
                        </div>
                          <div class="form-group">
                          <button style="background-color:#36b9cc;border-color:#36b9cc" class="btn btn-primary" id="addnew" onclick="addNewPart()">Add another part</button>
                          </div>

                            @if ($errors->any())
                              @foreach ($errors->all() as $error)
                              
                                <div class="alert alert-danger">{{$error}}</div>
                              
                              @endforeach
                                @endif
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Save all content</button>
                                </div>
                                @if(isset($msg))
                              <div style="width:100%;margin-top:20px" class="alert alert-{{ $type_msg }}">
                              <p>{{ $msg }}</p>
                              </div>
                              @endif
                                <script>

 var path_absolute = "{{env("PATH_ABSOLUTE_TINYMCE")}}";
  tinymce.init({
mode : "exact",
elements :"editor1",
path_absolute : "{{env("PATH_ABSOLUTE_TINYMCE")}}",
plugins: [
      "advlist autolink lists link image charmap print preview hr anchor pagebreak",
      "searchreplace wordcount visualblocks visualchars code fullscreen",
      "insertdatetime media nonbreaking save table contextmenu directionality",
      "emoticons template paste textcolor colorpicker textpattern"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | image ",
    relative_urls: false,
    file_browser_callback : function(field_name, url, type, win) {
      var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
      var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

      var cmsURL = path_absolute + 'laravel-filemanager?field_name=' + field_name;
      if (type == 'image') {
        cmsURL = cmsURL + "&type=Images";
      } else {
        cmsURL = cmsURL + "&type=Files";
      }

      tinyMCE.activeEditor.windowManager.open({
        file : cmsURL,
        title : 'Filemanager',
        width : x * 0.8,
        height : y * 0.8,
        resizable : "yes",
        close_previous : "no"
      });
    }

});
  



  </script>

                          </form>
           </div>
        </div>

    </div>

    <div class="row">
            <div class="card col-12 shadow mb-4">
                  <div class="card-header py-3">All lessons with their content</div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th>Lesson</th>
                            <th>@sortablelink('topic_contents.name','topic')</th>
                            <th>Preview/Edit</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($topics_content as $topic_content)
                          <tr>
                            <td>{{$topic_content->lessonName($topic_content->lesson_id)}}</td>
                            <td>{{$topic_content->topicName($topic_content->topics_id)}}</td>
                            <td><a data-toggle="modal"
                              data-target="#update-modal" data-id="{{ $topic_content->id }}"
                              href="#"><i class="fas fa-pencil-alt"></i></a></td>
                            <td><a class="remove-topic-content" data-id="{{ $topic_content->id }}"
                                href="#"><i class="fas fa-trash-alt"></i></a></td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                      {{ $topics_content->links() }}
                    </div>
                  </div>
                </div>
  
  </div>


  <div style="display:none" id="newPart">
      <div class="form-group">
          <label>Heading</label>
          <input type="text" class="form-control input-heading" id="topicName" name="heading" placeholder="Enter the heading of this part" required>
        </div>
      <div class="form-group">
          <textarea name="content" class="my-editor" id="editor1" rows="10" cols="80"></textarea>

      </div>
    </div>





@include('topicsContent.modal.update')
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('js/topicContent.js')}}"></script>

<script type="text/javascript">

  var count = 1;

  function addNewPart()
  {
  
    count = count +1;
    var div1 = document.createElement('div');
    div1.className = "form-group"+count;
    // Get template data
    div1.innerHTML = document.getElementById('newPart').innerHTML;
    // append to our form, so that template data
    //become part of form
    document.getElementById('partDiv').appendChild(div1);

    div1.querySelector('.input-heading').name = 'heading'+count;
    div1.querySelector('.my-editor').name = 'content'+count;
    div1.querySelector('.my-editor').id = 'editor'+count;

    console.log("editor"+count);

    tinymce.init({
      mode : "exact",
elements :'editor'+count,
path_absolute : "{{env("PATH_ABSOLUTE_TINYMCE")}}",
plugins: [
      "advlist autolink lists link image charmap print preview hr anchor pagebreak",
      "searchreplace wordcount visualblocks visualchars code fullscreen",
      "insertdatetime media nonbreaking save table contextmenu directionality",
      "emoticons template paste textcolor colorpicker textpattern"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | image ",
    relative_urls: false,
    file_browser_callback : function(field_name, url, type, win) {
      var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
      var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

      var cmsURL = path_absolute + 'laravel-filemanager?field_name=' + field_name;
      if (type == 'image') {
        cmsURL = cmsURL + "&type=Images";
      } else {
        cmsURL = cmsURL + "&type=Files";
      }

      tinyMCE.activeEditor.windowManager.open({
        file : cmsURL,
        title : 'Filemanager',
        width : x * 0.8,
        height : y * 0.8,
        resizable : "yes",
        close_previous : "no"
      });
    }

});

  
  }
  
  </script>
