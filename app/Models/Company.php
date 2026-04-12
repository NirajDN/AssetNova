<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['name', 'slug', 'industry', 'logo_url', 'primary_color'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function parts()
    {
        return $this->hasMany(Part::class);
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
