@extends('index')
@section('content-header')
    <h3 class="custom-h3">Import CSV</h3>
@stop
@section('content')
<div class="content-height">
  <form action="{{ route('save-csv-to-db') }}" method="post" enctype="multipart/form-data">
  @csrf
    <label for="file-csv">Select csv file:</label>
    <input type="file" id="file-csv" name="file_csv"><br>
  @error('file_type')
      <div class="error">{{ $message }}</div>
  @enderror
  @error('file_csv')
      <div class="error">{{ $message }}</div>
  @enderror
    <button class="btn btn-primary"type="submit">Import</button>
  </form>
</div>
@stop