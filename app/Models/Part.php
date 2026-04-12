<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $fillable = [
        'company_id', 'sku', 'name', 'description', 'category_id', 'supplier_id',
        'cost', 'stock_quantity', 'min_threshold', 'location', 'image_url',
    ];

    public function company()    { return $this->belongsTo(Company::class); }
    public function category()   { return $this->belongsTo(Category::class); }
    public function supplier()   { return $this->belongsTo(Supplier::class); }
    public function transactions(){ return $this->hasMany(Transaction::class); }
}
