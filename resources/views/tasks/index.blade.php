@extends('layouts.app')
@section('title','Tâches')
@section('content')
<div class="card">
  <div class="card-header"><h3 class="card-title">Tâches</h3></div>
  <div class="card-body">
    <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">Nouvelle tâche</a>
    <table class="table table-striped">
      <thead><tr><th>Titre</th><th>Due</th><th></th></tr></thead>
      <tbody>
        @foreach($tasks as $t)
        <tr>
          <td>{{ $t->title }}</td>
          <td>{{ $t->due_date?->format('Y-m-d') }}</td>
          <td><a href="{{ route('tasks.edit',$t) }}" class="btn btn-sm btn-warning">Éditer</a></td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {{ $tasks->links() }}
  </div>
</div>
@endsection
