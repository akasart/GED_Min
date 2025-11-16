@extends('layouts.app')

@section('title', 'Documents Rejetés')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Documents Rejetés</h5>
    </div>

    <div class="card">
        <div class="card-header bg-danger text-white">
            <h6 class="mb-0">Liste des documents rejetés</h6>
        </div>
        <div class="card-body">
            @if($documents->isEmpty())
                <p>Aucun document rejeté trouvé.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Titre</th>
                            <th>Type</th>
                            <th>Agent</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $doc)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $doc->titre }}</td>
                            <td>{{ $doc->type->libelle ?? 'N/A' }}</td>
                            <td>{{ $doc->agent->nom ?? 'N/A' }}</td>
                            <td>{{ $doc->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection