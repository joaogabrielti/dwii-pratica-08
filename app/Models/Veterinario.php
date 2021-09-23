<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Veterinario extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = ['nome', 'crmv', 'especialidade_id'];

    public function especialidade() {
        return $this->belongsTo(Especialidade::class);
    }
}
