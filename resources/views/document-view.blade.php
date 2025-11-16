@extends('layouts.app')
@section('title','Document viewer')
@section('content')
<div class="card">
  <div class="card-header"><h3 class="card-title">Document viewer</h3></div>
  <div class="card-body">
    <iframe src="{{ asset('design/document-view.html') }}" style="width:100%;height:80vh;border:0"></iframe>
  </div>
</div>
@endsection
