<!-- Operator Information Section (Read-only) -->
<div class="card mb-3">
  <div class="card-header bg-light py-2">
    <h6 class="mb-0" style="font-size: 0.9rem;"><i class="fas fa-user mr-2"></i>Opérateur (Lecture seule)</h6>
  </div>
  <div class="card-body py-3">
    <div class="row">
      <div class="col-md-6 col-lg-3 mb-2">
        <div class="form-group mb-2">
          <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Nom de l'opérateur</label>
          <input type="text" class="form-control form-control-sm" readonly value="{{ optional(optional(auth()->user())->agent)->nom ?? auth()->user()->name ?? 'N/A' }}" style="font-size: 0.85rem; background-color: #e9ecef;">
        </div>
      </div>
      <div class="col-md-6 col-lg-3 mb-2">
        <div class="form-group mb-2">
          <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Prénom de l'opérateur</label>
          <input type="text" class="form-control form-control-sm" readonly value="{{ optional(optional(auth()->user())->agent)->prenom ?? '' }}" style="font-size: 0.85rem; background-color: #e9ecef;">
        </div>
      </div>
      <div class="col-md-6 col-lg-3 mb-2">
        <div class="form-group mb-2">
          <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Matricule</label>
          <input type="text" class="form-control form-control-sm" readonly value="{{ optional(optional(auth()->user())->agent)->matricule ?? 'N/A' }}" style="font-size: 0.85rem; background-color: #e9ecef;">
        </div>
      </div>
      <div class="col-md-6 col-lg-3 mb-2">
        <div class="form-group mb-2">
          <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Ministère</label>
          <input type="text" class="form-control form-control-sm" readonly value="{{ optional(optional(auth()->user())->agent)->ministere_rattachement ?? 'N/A' }}" style="font-size: 0.85rem; background-color: #e9ecef;">
        </div>
      </div>
    </div>
  </div>
</div>
