<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['direction_id', 'name'];

    public function direction()
    {
        return $this->belongsTo(Direction::class);
    }
}
