
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
      <div class="sidebar-brand-icon">
        <img src="images/logo.png" width="150" alt="logo">
      </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
      <a class="nav-link {{ (request()->is('home*')) ? 'active' : '' }}" href="{{ route('home')}}">
        <i class="fas fa-fw fa-tachometer-alt {{ (request()->is('home*')) ? 'active' : '' }}"></i>
        <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
      Content
    </div>

    <!-- Nav Item - Topics -->
    <li class="nav-item">
      <a class="nav-link collapsed {{ (request()->is('topic')) ? 'active' : '' }}" href="{{ route('topic')}}">
        <i class="fa fa-chalkboard {{ (request()->is('topic')) ? 'active' : '' }}"></i>
        <span>Topics</span>
      </a>
    </li>

    <!-- Nav Item - Lessons -->
    <li class="nav-item">
      <a class="nav-link collapsed {{ (request()->is('lesson*')) ? 'active' : '' }}" href="{{ route('lesson')}}">
        <i class="fa fa-graduation-cap {{ (request()->is('lesson*')) ? 'active' : '' }}"></i>
        <span>Lessons</span>
      </a>
    </li>

    <!-- Nav Item - Topics Content -->
    <li class="nav-item">
      <a class="nav-link collapsed {{ (request()->is('topic-content*')) ? 'active' : '' }}" href="{{ route('topic.content')}}">
        <i class="fa fa-book-medical {{ (request()->is('topic-content*')) ? 'active' : '' }}"></i>
        <span>Lesson's content</span>
      </a>
    </li>

    <!-- Nav Item - Games -->
    <li class="nav-item">
      <a class="nav-link collapsed {{ (request()->is('game*')) ? 'active' : '' }}" href="{{ route('game')}}">
        <i class="fa fa-gamepad {{ (request()->is('game*')) ? 'active' : '' }}"></i>
        <span>Games</span>
      </a>
      
    </li>
    <!-- Nav Item - Live Cases -->
    <li class="nav-item">
      <a class="nav-link collapsed {{ (request()->is('liveCase*')) ? 'active' : '' }}" href="{{ route('live.case')}}">
        <i class="fa fa-syringe {{ (request()->is('liveCase*')) ? 'active' : '' }}"></i>
        <span>Live Cases</span>
      </a>
      
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
      Admin
    </div>

    <!-- Nav Item - Users -->
    <li class="nav-item">
      <a class="nav-link collapsed {{ (request()->is('user-app*')) ? 'active' : '' }}" href="{{ route('user.app')}}" >
        <i class="fa fa-users {{ (request()->is('user-app*')) ? 'active' : '' }}"></i>
        <span>App Users</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link collapsed {{ (request()->is('statistics*')) ? 'active' : '' }}" href="{{ route('statistics')}}" >
        <i class="fa fa-users {{ (request()->is('statistics*')) ? 'active' : '' }}"></i>
        <span>Statistics</span>
      </a>
    </li>

    <!-- Nav Item - Notifications -->
    <li class="nav-item">
      <a class="nav-link collapsed {{ (request()->is('notification*')) ? 'active' : '' }}" href="{{ route('notification')}}" >
        <i class="fa fa-flag {{ (request()->is('notification*')) ? 'active' : '' }}"></i>
        <span>Notifications</span>
      </a>
    </li>

    <!-- Nav Item - Settings -->
    <li class="nav-item">
      <a class="nav-link collapsed {{ (request()->is('settings*')) ? 'active' : '' }}" href="{{ route('settings')}}">
        <i class="fa fa-cog {{ (request()->is('settings*')) ? 'active' : '' }}"></i>
        <span>Settings</span>
      </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">


  </ul>
  <!-- End of Sidebar -->