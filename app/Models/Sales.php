<?php

namespace App\Models;

use App\Models\User;
use App\Models\Inventory;
use App\Models\SalesDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sales extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'number', 'date'];

    /**
     * Get the user that owns the Sales
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the salesdetail for the Sales
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function salesdetail()
    {
        return $this->hasMany(SalesDetail::class);
    }
}
