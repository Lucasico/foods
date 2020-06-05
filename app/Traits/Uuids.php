<?php
namespace App\Traits;

use Ramsey\Uuid\Uuid;

trait Uuids
{
    public static function boot ()
    {
        parent ::boot ();
        static ::creating ( function ( $model ) {
            $model -> id = Uuid ::uuid4 ();
        } );
    }
}

?>

