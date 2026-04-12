<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['company_id', 'name', 'contact_email', 'phone', 'address', 'rating'];
    public function company() { return $this->belongsTo(Company::class); }
    public function parts()   { return $this->hasMany(Part::class); }
}
