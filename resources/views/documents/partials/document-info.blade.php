<!-- Document Information Section -->
<div class="card mb-3">
  <div class="card-header bg-light py-2">
    <h6 class="mb-0" style="font-size: 0.9rem;"><i class="fas fa-file-alt mr-2"></i>Informations du Document</h6>
  </div>
  <div class="card-body py-3">
    <div class="row">
      <div class="col-md-6 col-lg-4 mb-2">
        <div class="form-group mb-2">
          <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Référence</label>
          <div class="input-group input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text" style="font-size: 0.85rem;">DOC-{{ now()->year }}-</span>
            </div>
            @php
              $lastDoc = \App\Models\Document::orderBy('id', 'desc')->first();
              $nextNumber = $lastDoc ? str_pad($lastDoc->id + 1, 3, '0', STR_PAD_LEFT) : '001';
            @endphp
            <input type="text" class="form-control" value="{{ $nextNumber }}" readonly style="font-size: 0.85rem; background-color: #e9ecef;" disabled>
            <small class="text-muted" style="font-size: 0.75rem;">La référence sera générée automatiquement</small>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 mb-2">
        <div class="form-group mb-2">
          <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Date *</label>
          <input type="date" class="form-control form-control-sm" name="date_document" value="{{ old('date_document', now()->format('Y-m-d')) }}" required style="font-size: 0.85rem;">
          @error('date_document')<small class="text-danger" style="font-size: 0.75rem;">{{ $message }}</small>@enderror
        </div>
      </div>
      <div class="col-md-6 col-lg-4 mb-2">
        <div class="form-group mb-2">
          <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Intitulé *</label>
          <input type="text" class="form-control form-control-sm" name="titre" required placeholder="Saisir le titre du document" value="{{ old('titre') }}" style="font-size: 0.85rem;">
          @error('titre')<small class="text-danger" style="font-size: 0.75rem;">{{ $message }}</small>@enderror
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 col-lg-4 mb-2">
        <div class="form-group mb-2">
          <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Direction</label>
          <select class="form-control form-control-sm" name="direction_id" id="direction" style="font-size: 0.85rem;">
            <option value="">-- Sélectionner --</option>
            @foreach($directions as $dir)
              <option value="{{ $dir->id }}" {{ old('direction_id') == $dir->id ? 'selected' : '' }}>{{ $dir->libelle }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 mb-2">
        <div class="form-group mb-2">
          <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Service</label>
          <select class="form-control form-control-sm" name="service_id" id="service" style="font-size: 0.85rem;">
            <option value="">-- Sélectionner --</option>
            @foreach($services as $s)
              <option data-direction="{{ $s->direction_id }}" value="{{ $s->id }}" {{ old('service_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
  </div>
</div>
