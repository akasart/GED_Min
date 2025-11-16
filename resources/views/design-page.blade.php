@extends('layouts.app')
@section('title', 'Design page')
@section('content')
<div class="card">
  <div class="card-header"><h3 class="card-title">{{ $page }}</h3></div>
  <div class="card-body">
    <iframe src="{{ asset('design/' . ($page . '.html')) }}" style="width:100%;height:80vh;border:0"></iframe>
  </div>
</div>
@endsection
