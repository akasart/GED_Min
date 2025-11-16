<!-- File and Observation Section -->
<div class="card mb-3">
  <div class="card-header bg-light py-2">
    <h6 class="mb-0" style="font-size: 0.9rem;"><i class="fas fa-upload mr-2"></i>Fichier et Observation</h6>
  </div>
  <div class="card-body py-3">
    <div class="form-group mb-2">
      <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Fichier *</label>
      <div class="custom-file">
        <input type="file" class="custom-file-input" id="documentFile" name="fichier" required style="font-size: 0.85rem;">
        <label class="custom-file-label" for="documentFile" style="font-size: 0.85rem;">Choisir un fichier</label>
      </div>
      <small class="form-text text-muted" style="font-size: 0.75rem;">PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (Max. 10 MB)</small>
      @error('fichier')<small class="text-danger" style="font-size: 0.75rem;">{{ $message }}</small>@enderror
    </div>

    <div class="form-group mb-2">
      <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Observation</label>
      <textarea class="form-control form-control-sm" name="observation" rows="2" placeholder="Description ou remarque..." style="font-size: 0.85rem;">{{ old('observation') }}</textarea>
    </div>
  </div>
</div>
