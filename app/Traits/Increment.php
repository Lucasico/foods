<?php
namespace App\Traits;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
trait Increment
{
    public static function boot() {
        parent::boot();
        static::creating(function ($model) {
            $model -> id = Uuid ::uuid4 ();
            $model->codigo = DB::table('pedidos')->count();
        });
    }
}

