<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre', 'fichier', 'date_creation', 'etat', 'observation', 'agent_id', 'document_type_id', 'created_by',
        'motif_rejet', 'validated_by', 'validated_at', 'rejected_by', 'rejected_at'
    ];

    protected $casts = [
        'date_creation' => 'datetime',
        'validated_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function rejector()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function histories()
    {
        return $this->hasMany(DocumentHistory::class);
    }

    /**
     * Check if document is pending
     */
    public function isPending()
    {
        return $this->etat === 'En attente';
    }

    /**
     * Check if document is validated
     */
    public function isValidated()
    {
        return $this->etat === 'Validé';
    }

    /**
     * Check if document is rejected
     */
    public function isRejected()
    {
        return $this->etat === 'Rejeté';
    }

    /**
     * Check if document is archived
     */
    public function isArchived()
    {
        return $this->etat === 'Archivé';
    }

    public function getFormattedDateCreationAttribute()
    {
        if (!$this->date_creation) {
            return '-';
        }
        
        try {
            return $this->date_creation->format('Y-m-d');
        } catch (\Exception $e) {
            return $this->date_creation;
        }
    }
}
