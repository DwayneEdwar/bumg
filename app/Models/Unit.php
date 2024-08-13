<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class Unit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'unit',
        'alamat',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($resort) {
            if (empty($resort->id)) {
                $resort->id = IdGenerator::generate([
                    'table' => 'units',
                    'length' => 15,
                    'prefix' => 'UNIT-'
                ]);
            }
        });
    }


    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }
}
