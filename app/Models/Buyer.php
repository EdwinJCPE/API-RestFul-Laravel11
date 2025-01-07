<?php

namespace App\Models;

use App\Models\Transaction;
use App\Models\Scopes\BuyerScope;
use App\Transformers\BuyerTransformer;
use Illuminate\Database\Eloquent\Relations\HasMany;

// use Illuminate\Database\Eloquent\Model;

class Buyer extends User
{
    public $transformer = BuyerTransformer::class;

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope(new BuyerScope);
    // }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // parent::booted();
        static::addGlobalScope(new BuyerScope);
    }

    public function transactions(): HasMany
    {
        // return $this->hasMany('App\Models\Transaction'); // Un comprador tiene muchas transacciones
        // return $this->hasMany(Transaction::class, 'buyer_id'); // Un comprador tiene muchas transacciones
        return $this->hasMany(Transaction::class); // Un comprador tiene muchas transacciones
    }

    public function scopeHasTransactions($query)
    {
        // dd($query);
        $query->has('transactions');
        // $query->has('transactions')->orderBy('id', 'asc');
    }

    public function scopeIdAscending($query)
    {
        // dd($query);
        $query->orderBy('id', 'asc');
    }
}
