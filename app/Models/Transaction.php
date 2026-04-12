<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['company_id', 'part_id', 'type', 'quantity', 'notes'];
    public function company() { return $this->belongsTo(Company::class); }
    public function part()    { return $this->belongsTo(Part::class); }
}
