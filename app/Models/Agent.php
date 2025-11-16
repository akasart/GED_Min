<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricule', 'nom', 'prenom', 'direction', 'service', 
        'fonction', 'ministere_rattachement', 'date_entree', 'user_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_entree' => 'date',
    ];

    // Removed auto-generation of matricule - now it's a free field

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateMatricule()
    {
        $year = now()->year;
        $lastAgent = self::where('matricule', 'like', "MTF-{$year}-%")
            ->orderBy('matricule', 'desc')
            ->first();
        
        if ($lastAgent) {
            $lastNumber = (int) substr($lastAgent->matricule, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return "MTF-{$year}-" . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}
