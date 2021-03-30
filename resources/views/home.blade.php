@extends('layouts.app')
@section('nav-bar')
<nav class="navbar bg-primary navbar-dark navbar-expand-sm">

  <!-- Links -->
  <ul class="nav navbar-nav" style="visibility: visible;">
    <li class="nav-item">
      <a class="nav-link active">TaskManager</a>
    </li>
    <li class="nav-item">
        <router-link  class="nav-link" to="/tasks"><i class="fas fa-list"></i><span class="a-text pl-1">Tasks</span></router-link>
    </li>
    <li class="nav-item">
        <router-link  class="nav-link" to="/import-csv"><i class="fas fa-file-download"></i><span class="a-text pl-1">Import CSV</span></router-link>
    </li>
    <li class="nav-item">
        <router-link  class="nav-link" to="/clients-map"><i class="fas fa-map-marker-alt"></i><span class="a-text pl-1">Clients Map</span></router-link>
    </li>
    <li class="nav-item">
        <router-link  class="nav-link" to="/reports"><i class="fas fa-chart-bar" aria-hidden="true"></i></i><span class="a-text pl-1">Reports</span></router-link>
    </li>
    <li class="nav-item">
        <router-link  class="nav-link" to="/mail"><i class="fas fa-envelope"></i><span class="a-text pl-1">Mail</span></router-link>
    </li>
    <li class="nav-item">
        <router-link  class="nav-link" to="/project-info"><i class="fas fa-info" aria-hidden="true"></i></i><span class="a-text pl-1">Project Info</span></router-link>
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
                            <i class="fas fa-sign-out-alt"></i> {{ Auth::user()->name }} <span class="caret"></span>
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
