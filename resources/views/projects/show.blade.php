@extends('layouts.app')
@section('title','Projet')
@section('content')
<div class="card">
  <div class="card-header"><h3 class="card-title">{{ $project->name }}</h3></div>
  <div class="card-body">
    <p><strong>Description:</strong> {{ $project->description }}</p>
    <p><strong>Start:</strong> {{ $project->start_date?->format('Y-m-d') }}</p>
    <p><strong>End:</strong> {{ $project->end_date?->format('Y-m-d') }}</p>
    <p><strong>Budget:</strong> {{ $project->budget }}</p>
  </div>
</div>
@endsection
{{-- projects removed --}}
