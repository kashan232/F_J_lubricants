<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salesman extends Model
{
    use HasFactory;

    // Explicitly define the table and connection if the default naming convention doesn't match
    protected $table = 'salesmen'; // Ensure the table is correct
    protected $connection = 'mysql'; // Ensure the correct DB connection is being used

    // Define fillable fields
    protected $fillable = ['name', 'email', 'phone_number', 'address', 'status', 'city_id', 'area_id', 'admin_or_user_id'];

    // Relationships with other tables (if any)
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'admin_or_user_id');
    }
}
