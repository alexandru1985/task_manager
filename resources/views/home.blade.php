@extends('layouts.app')
@section('nav-bar')
<nav class="navbar bg-primary navbar-dark navbar-expand-sm">

  <!-- Links -->
  <ul class="nav navbar-nav" style="visibility: visible;">
    <li class="nav-item">
      <a class="nav-link active">Task Manager</a>
    </li>
    <li class="nav-item">
        <router-link  class="nav-link" to="/task_manager/tasks"><i class="fa fa-list"></i><span class="a-text pl-1">Tasks</span></router-link>
    </li>
    <li class="nav-item">
        <router-link  class="nav-link" to="/task_manager/import-csv"><i class="fa fa-download"></i><span class="a-text pl-1">Import CSV</span></router-link>
    </li>
    <li class="nav-item float-right">
     
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <i class="fa fa-sign-out"></i> {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
    </li>
  </ul>

</nav>
@endsection
@section('content')
<router-view></router-view>
@endsection
