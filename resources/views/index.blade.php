<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <meta name="csrf-token" content="{{ csrf_token() }}"/>
  <link rel="stylesheet" href="{{ asset('public/css/fontawesome/css/all.min.css') }}"/>
  <link rel="stylesheet" href="{{ asset('public/css/bootstrap.min.css') }}"/>
  <link rel="stylesheet" href="{{ asset('public/css/custom.css') }}"/>
  <link rel="stylesheet" href="{{ asset('public/css/select2.min.css') }}"/>
  <link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css"/>
  <script src="{{ asset('public/js/jquery.min.js') }}"></script>
  <script src="{{ asset('public/js/popper.min.js') }}"></script>
  <script src="{{ asset('public/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('public/js/custom.js') }}"></script>
  <script src="{{ asset('public/js/select2.min.js') }}"></script>
  <script src="{{ asset('public/js/app.js') }}" type="module"></script>
  <!-- use the latest vue-select release -->
<script src="https://unpkg.com/vue-select@latest"></script>
</head>
<body>
<div id ="app" class="container-fluid custom-container">
    <div class="row">
        <div class="col-12">
            <section class="content-header">
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="#">Task Manager</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarColor02">
      <ul class="navbar-nav mr-auto">
        <li id="first-li" class="nav-item">
        <router-link class="nav-link" to="/task_manager/tasks"><i class="fa fa-list"></i> Tasks</router-link>
        </li>
        <li class="nav-item">
        <router-link class="nav-link" to="/task_manager/import-csv"><i class="fa fa-download"></i> Import CSV</router-link>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><i class="fa fa-map"></i> Clients Map</a>
        </li>
      </ul>
    </div>
  </nav> 
            </section>
        </div>
    </div>   
    <div class="row">
        <div class="col-12">
            <section class="content-header">
            </section> 
        </div>    
    </div>
    <div class="row">
        <div class="col-12 content-height">
        <section class="content">  
            <router-view></router-view>
        </section>
        </div>
    </div>
</div>
</body>
</html>