<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salesman extends Model
{
    use HasFactory;

    protected $table = 'sales_mens'; // Explicitly define the table name
    
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    
    protected $fillable = [
        'admin_or_user_id',
        'name',
        'phone',
        'city',
        'area',
        'address',
        'salary',
        'status',
    ];
}
