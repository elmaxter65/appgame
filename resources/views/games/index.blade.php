@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Games</h1>
     </div>
    <div class="row">
      <div class="card col-12 mb-4">
            <div class="card-header">Create a new game</div>
            <div class="card-body">

                    @if(empty($topics) || empty($lessons))
                    <p>You need to create topics and lessons in order to start creating games</p>
                    @else

                    <form method="POST" id="formGame" action="{{ route('game') }}" class="row">
                            @csrf 
                            <div class="form-group col-md-6">
                                <label for="selectTopic">Select topic</label>
                                <input type="hidden" value="{{ route('lesson.get') }}" id="urlTopic">
                                <select class="form-control" id="selectTopic" name="topics_id" required>
                                        @foreach($topics as $topic)
                                                <option value="{{$topic->id}}">{{$topic->name}}</option>
                                        @endforeach
                                </select>
                                </div>
                            <div class="form-group col-md-6">
                                <label for="selectLesson">Select lesson</label>
                                <select class="form-control" id="selectLesson" name="lesson_id" required>
                                        @foreach($lessons as $lesson)
                                                <option id="selectLesson{{$lesson->id}}" value="{{$lesson->id}}">{{$lesson->name}}</option>
                                        @endforeach
                                </select>
                                </div>
                            <div class="form-group col-md-4">
                                <label for="selectLevel">Select level</label>
                                <select class="form-control" name="level" required>
                                      <option value="1">1</option>
                                      <option value="2" >2</option>
                                      <option value="3" >3</option>
                                      <option value="challenge" >challenge</option>
                                        
                                </select>
                                </div>
                            <div class="form-group col-md-4">
                                <label for="selectDynamic">Select dynamic type</label>
                                <select class="form-control" id="selectDynamic" name="dynamic_number" required>
                                      <option value="0">Multiple choice - 4 images</option>
                                      <option value="2">Correct image</option>
                                      <option value="3">Multiple choice - image</option>
                                      <option value="4">Multiple choice - text</option>
                                      <option value="5">Drag and drop spot</option>
                                      <option value="6">Slider match</option>
                                      <option value="8">Fill the gap</option>
                                </select>
                                </div>
                            <div class="form-group col-md-4">
                                <label for="points">Points</label>
                                  <input required class="form-control" type="number" name="points" id="points" placeholder="Enter the number of points this game will give">
                                </div>
                            <div class="form-group col-md-12">
                                <label for="points">Title</label>
                                  <input required class="form-control" type="text" name="title" id="title" placeholder="Enter the title of the game">
                                </div>
                            <div class="form-group col-md-12">
                                <label for="points">Question/description</label>
                                  <input class="form-control" type="text" name="question" id="question" placeholder="Enter the description of the game">
                                </div>
                                @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                
                                  <div class="alert alert-danger col-12">{{$error}}</div>
                                
                                @endforeach
                                  @endif
                                  <div class="form-group col-md-12">
                                    <label for="points">Images references</label>
                                    <textarea class="form-control" name="images_ref" id="images_ref"></textarea>
                                      
                                    </div>
                            <button type="submit" class="btn btn-primary" style="margin-left: .75rem;">Create</button>
                            @if(isset($msg))
                              <div style="width:100%;margin-top:20px" class="alert alert-{{ $type_msg }}">
                              <p>{{ $msg }}</p>
                              </div>
                              @endif
                          </form>

                          @endif

           </div>
        </div>

    </div>


    <div class="row">
        <div class="card col-12 shadow mb-4">
            <div class="card-header py-3">All games</div>
            <div class="card-body">
              <div class="table-responsive">
                  @if(isset($errorContent))
                  <div class="alert alert-danger">
                      <p>That game already has content defined. If you want to change it, you need to delete it and then create it again.</p>
                  </div>
                  @endif
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Title</th>
                      <th>Lesson</th>
                      <th>Level</th>
                      <th>Dynamic</th>
                      <th>Points</th>
                      <th>Edit</th>
                      <th>Content</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($games as $game)
                    <tr>
                      <td>{{$game->title}}</td>
                      <td>{{$game->getLessonName($game->lesson_id)}}</td>
                      <td>{{$game->level}}</td>
                      <td>{{$game->dynamic_number}}</td>
                      <td>{{$game->points}}</td>
                      <td><a data-toggle="modal"
                        data-target="#update-modal" data-id="{{ $game->id }}"
                        href="#"><i class="fas fa-pencil-alt"></i></a></td>
                        <td><a class="show-content-game" data-id="{{ $game->id }}"
                            href="{{ route('game.show.content', $game->id)}}"><i class="fas fa-plus"></i></a></td>
                      <td><a class="remove-game" data-id="{{ $game->id }}"
                          href="#"><i class="fas fa-trash-alt"></i></a></td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>

    </div>
    

    @include('games.modal.update')
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('js/game.js')}}"></script>

