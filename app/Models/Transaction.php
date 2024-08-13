<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class Transaction extends Model
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
        'unit_id',
        'tanggal_transaksi',
        'jenis_transaksi',
        'quantity',
        'satuan',
        'harga_satuan',
        'total',
        'deskripsi',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //'unit_id' => 'String',
        'tanggal_transaksi' => 'date',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($resort) {
            if (empty($resort->id)) {
                $resort->id = IdGenerator::generate([
                    'table' => 'transactions',
                    'length' => 20,
                    'prefix' => 'TRK-'
                ]);
            }
        });

    }
 }





    


