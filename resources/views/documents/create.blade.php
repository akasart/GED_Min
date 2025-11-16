@extends('layouts.app')

@section('title', 'Ajouter un document')

@section('content')
  <div class="container-fluid" style="font-size: 0.875rem;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 style="font-size: 1.1rem; margin: 0;">Formulaire ajout document</h5>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0" style="font-size: 0.8rem;">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
          <li class="breadcrumb-item active">Ajouter Document</li>
        </ol>
      </nav>
    </div>

    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white py-2">
        <h6 class="card-title mb-0" style="font-size: 0.9rem;"><i class="fas fa-file-alt mr-2"></i>Formulaire de Saisie</h6>
      </div>
      <div class="card-body">
        <!-- Display Validation Errors -->
        @if($errors->any())
          <div class="alert alert-danger">
            <ul style="margin-bottom: 0; font-size: 0.85rem;">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" id="documentForm">
          @csrf
          <input type="hidden" name="agent_id" id="agent_id" value="{{ old('agent_id', optional(optional(auth()->user())->agent)->id ?? auth()->id()) }}">

          <!-- Operator Information Partial -->
          @include('documents.partials.operator-info')

          <!-- Document Information Partial -->
          @include('documents.partials.document-info')

          <!-- File Section Partial -->
          @include('documents.partials.file-section')


          <div class="text-right mt-3">
            <a href="{{ route('documents.index') }}" class="btn btn-secondary btn-sm mr-2" style="font-size: 0.85rem;"><i class="fas fa-times mr-1"></i>Annuler</a>
            <button type="submit" class="btn btn-primary btn-sm" style="font-size: 0.85rem;"><i class="fas fa-save mr-1"></i>Ajouter le document</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Inline scripts from design: date autofill, service cascade, file label --}}
  @push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // dateSaisie already filled server-side; keep client fallback
      const dateField = document.getElementById('dateSaisie');
      if (dateField && !dateField.value) {
        const today = new Date();
        const formattedDate = today.toLocaleDateString('fr-FR');
        dateField.value = formattedDate;
      }

      // services by direction
      const servicesByDirection = {
        'DRH': ['Recrutement', 'Formation', 'Paie', 'Relations sociales'],
        'DAF': ['Comptabilité', 'Finance', 'Budget', 'Marchés publics'],
        'DSI': ['Développement', 'Infrastructure', 'Support', 'Sécurité'],
        'DG': ['Secrétariat', 'Communication', 'Audit', 'Stratégie']
      };

      const directionSelect = document.getElementById('direction');
      const serviceSelect = document.getElementById('service');

      if (directionSelect) {
        directionSelect.addEventListener('change', function() {
          const selected = this.value;
          serviceSelect.innerHTML = '<option value="">-- Sélectionner un service --</option>';
          // show services that belong to the selected direction
          document.querySelectorAll('#service option[data-direction]').forEach(opt => {
            if (!selected || opt.getAttribute('data-direction') == selected) {
              serviceSelect.appendChild(opt.cloneNode(true));
            }
          });
        });
      }

      // file input label and auto-select type by extension (hidden mapping -> document_type_id)
      const docFile = document.getElementById('documentFile');
      const typeSelect = document.getElementById('document_type_select');
      const typeHidden = document.getElementById('document_type_id');
      if (docFile) {
        docFile.addEventListener('change', function(e) {
          const file = e.target.files[0];
          const fileName = file?.name || 'Choisir un fichier';
          const label = document.querySelector('.custom-file-label');
          if (label) label.textContent = fileName;

          if (file && typeSelect && typeHidden) {
            const ext = (file.name.split('.').pop() || '').toLowerCase();
            // try to match option by data-ext
            let matched = false;
            Array.from(typeSelect.options).forEach(opt => {
              const dataExt = (opt.getAttribute('data-ext') || '').toLowerCase();
              if (!dataExt) return;
              const parts = dataExt.split(',').map(s => s.trim());
              if (parts.includes(ext)) {
                // set hidden value to the matched id
                typeHidden.value = opt.value;
                matched = true;
              }
            });
            if (!matched) {
              // no match: try simple mapping
              const fallback = { 'pdf':'pdf', 'doc':'doc','docx':'doc','xls':'xls','xlsx':'xls','jpg':'jpg','jpeg':'jpg','png':'jpg' };
              const key = fallback[ext];
              if (key) {
                Array.from(typeSelect.options).forEach(opt => {
                  const dataExt = (opt.getAttribute('data-ext') || '').toLowerCase();
                  if (dataExt.includes(key)) typeHidden.value = opt.value;
                });
              }
            }
          }
        });
      }
    });
  </script>
  @endpush

@endsection

