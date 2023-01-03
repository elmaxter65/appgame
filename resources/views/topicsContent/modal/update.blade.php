@extends('layouts.modal.update.normal')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/modal.css')}}">
@endpush

@section('modal_form')
    <form method="POST" action="{{ route('topic.content') }}" enctype="multipart/form-data" novalidate>
        <div class="modal-body">
            @method('put')
            @csrf
            <input type="hidden" name="topic_content_id" id="topic_content_id"/>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger" style="display:none"></div>
                </div>
            </div>
            <div class="form-group">
              <p>Here you can update this part of the lesson's content</p>
                <label>Heading</label>
                <input type="text" class="form-control" id="heading" name="heading" placeholder="Enter the heading of this part" required value="{{old('heading')}}">
              </div>

            <div class="form-group">
                
                <textarea required name="content" class="my-editor" id="editor1_update" rows="10" cols="80">{{old('content')}}</textarea>
                @if ($errors->has('name'))
                    <span class="invalid-feedback"
                          role="alert"><strong>{{ $errors->first('name') }}</strong></span>
                @endif
            </div>
            <script>
                
                                    var editor_config = {
    path_absolute : "{{env("PATH_ABSOLUTE_TINYMCE")}}",
    selector: "textarea.my-editor",
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

      var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
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
  };

  tinymce.init(editor_config);
 
            </script>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
            <button type="submit" class="btn btn-primary ld-ext-left">{{__('Update')}}
                <div class="ld ld-ring ld-spin"></div>
            </button>
        </div>
    </form>
@endsection
