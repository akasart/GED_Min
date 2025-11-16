@extends('layouts.app')
@section('title', 'Détail Faritra')
@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white"><h4 class="mb-0">Détail Faritra</h4></div>
                <div class="card-body text-center">
                    <h2 class="mb-3">{{ $faritra->val }}</h2>
                    @if($faritra->image)
                        <img src="{{ asset('storage/' . $faritra->image) }}" alt="Logo Faritra" class="img-thumbnail mb-3" width="150">
                    @endif
                    <p><strong>Description:</strong> {{ $faritra->desc }}</p>
                    <p><strong>Localisation:</strong> {{ $faritra->localisation }}</p>
                    <a href="{{ route('faritra.edit', $faritra) }}" class="btn btn-warning">Editer</a>
                    <a href="{{ route('faritra.index') }}" class="btn btn-secondary">Retour</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
