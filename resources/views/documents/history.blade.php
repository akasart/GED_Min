<div class="timeline">
  @if($histories->isEmpty())
    <div class="text-center py-4">
      <i class="fas fa-history fa-2x text-muted mb-2"></i>
      <p class="text-muted mb-0">Aucun historique trouvé pour ce document</p>
    </div>
  @else
    @foreach($histories as $history)
      <div class="timeline-item">
        <div class="timeline-marker">
          @if($history->action == 'created')
            <i class="fas fa-plus-circle text-success"></i>
          @elseif($history->action == 'updated')
            <i class="fas fa-edit text-warning"></i>
          @elseif($history->action == 'validated')
            <i class="fas fa-check-circle text-success"></i>
          @elseif($history->action == 'rejected')
            <i class="fas fa-times-circle text-danger"></i>
          @elseif($history->action == 'archived')
            <i class="fas fa-archive text-info"></i>
          @elseif($history->action == 'resubmitted')
            <i class="fas fa-redo text-warning"></i>
          @elseif($history->action == 'downloaded')
            <i class="fas fa-download text-primary"></i>
          @elseif($history->action == 'deleted')
            <i class="fas fa-trash text-danger"></i>
          @else
            <i class="fas fa-circle text-secondary"></i>
          @endif
        </div>
        <div class="timeline-content">
          <div class="timeline-header">
            <h6 class="mb-1">
              @if($history->action == 'created')
                Document créé
              @elseif($history->action == 'updated')
                Document modifié
              @elseif($history->action == 'validated')
                Document validé
              @elseif($history->action == 'rejected')
                Document rejeté
              @elseif($history->action == 'archived')
                Document archivé
              @elseif($history->action == 'resubmitted')
                Document renvoyé
              @elseif($history->action == 'downloaded')
                Document téléchargé
              @elseif($history->action == 'deleted')
                Document supprimé
              @else
                Action effectuée
              @endif
            </h6>
            <small class="text-muted">{{ $history->created_at->format('d/m/Y H:i') }}</small>
          </div>
          <div class="timeline-body">
            <p class="mb-1">
              <strong>{{ $document->titre }}</strong>
              @if($document->type)
                <span class="text-muted">({{ $document->type->libelle }})</span>
              @endif
            </p>
                  <p class="mb-0 small text-muted">
                    Par: {{ $history->user->username ?? 'Utilisateur inconnu' }}
              @if($history->agent)
                | Agent: {{ $history->agent->nom }} {{ $history->agent->prenom }}
              @endif
            </p>
            @if($history->description)
              <p class="mb-0 small">{{ $history->description }}</p>
            @endif
            @if($history->action == 'rejected' && $document->motif_rejet)
              <div class="alert alert-danger mt-2 mb-0">
                <strong>Motif de rejet :</strong> {{ $document->motif_rejet }}
              </div>
            @endif
          </div>
        </div>
      </div>
    @endforeach
  @endif
</div>

<style>
.timeline {
  position: relative;
  padding-left: 30px;
}

.timeline-item {
  position: relative;
  margin-bottom: 30px;
  padding-left: 40px;
}

.timeline-item:before {
  content: '';
  position: absolute;
  left: 15px;
  top: 0;
  bottom: -30px;
  width: 2px;
  background-color: #dee2e6;
}

.timeline-item:last-child:before {
  bottom: 0;
}

.timeline-marker {
  position: absolute;
  left: 0;
  top: 5px;
  width: 30px;
  height: 30px;
  background-color: #fff;
  border: 2px solid #dee2e6;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1;
}

.timeline-content {
  background-color: #f8f9fa;
  border-radius: 8px;
  padding: 15px;
  border-left: 4px solid #007bff;
}

.timeline-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

.timeline-body p {
  margin-bottom: 5px;
}
</style>
