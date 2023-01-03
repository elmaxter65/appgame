@extends('layouts.app')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        </div>

        <!-- Content Row -->
        <div class="row">

          <!-- Total users -->
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Number of users </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{$num_total_users}}</div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-users fa-2x text-gray-300"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Confirmed users </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">@if($num_total_users != 0) {{$num_users_confirmed}} ({{number_format(($num_users_confirmed/$num_total_users)*100,2,'.','')}}%) @else 0 @endif</div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-users fa-2x text-gray-300"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- New users -->
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Number of new users (last 7 days)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{$num_new_users}}</div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- Content Row -->
        <div class="row">

          <!-- Content Column -->
          <div class="col-lg-6 mb-4">

            <!-- Project Card Example -->
            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Average number of games completed</h6>
              </div>
              <div class="card-body">
                @foreach($topics as $topic)
                <h5 class="small font-weight-bold"> TOPIC: {{$topic->name}}</h5>
                @foreach($topic->lessons()->get() as $lesson)
              <h4 class="small font-weight-bold">{{$lesson->name}}<span class="float-right">{{$lesson->averageNumGamesCompleted()}} / {{ $lesson->games()->count()}}</span></h4>
                <div class="progress mb-4">
                  <div class="progress-bar {{$lesson->colorPercGamesCompleted()}}" role="progressbar" style="width: {{$lesson->averagePercGamesCompleted()}}%" aria-valuenow="{{$lesson->averagePercGamesCompleted()}}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                @endforeach
                @endforeach
                
                
              </div>
            </div>

            
          </div>

          <div class="col-lg-6 mb-4">

            <!--Time spent -->
            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Average time spent per topic</h6>
              </div>
              <div class="card-body">
                @foreach($topics as $topic)
              <p class="text-xs font-weight-bold text-uppercase mb-1">{{$topic->name}}</p>
                <p>{{$topic->averageTimeSpent()}} min</p>
                @endforeach

              </div>
            </div>

            <!--Points per round -->
            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Average points per round per topic</h6>
              </div>
              <div class="card-body">
                @foreach($topics as $topic)
              <p class="text-xs font-weight-bold text-uppercase mb-1">{{$topic->name}}</p>
                
              @foreach($topic->lessons()->get() as $lesson)
              <p class="text-xs font-weight-bold text-uppercase mb-1">{{$lesson->name}}</p>
                @for($i=0;$i<=$lesson->numMaxRounds();$i++)
                  <p class="text-xs font-weight-bold mb-1">Round {{$i}}</p>
              <p>{{$lesson->averagePointsPerRound($i)}} / {{$lesson->numTotalPoints()}}</p>
                @endfor

                @endforeach
                @endforeach

              </div>
            </div>

          </div>
        </div>


      <!-- /.container-fluid -->
@endsection
