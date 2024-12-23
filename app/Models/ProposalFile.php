<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalFile extends Model
{
    use HasFactory;

    protected $fillable = ['proposal_id', 'file_path'];

    public function proposal(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->belongsTo(Proposal::class);
    }
}
