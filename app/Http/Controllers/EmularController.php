<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmularController extends Controller
{
    public function index()
    {
        return response()->json([
            ['id' => 1, 'nome' => 'item 01'],
            ['id' => 2, 'nome' => 'item 02'],
            ['id' => 3, 'nome' => 'item 03'],
            ['id' => 4, 'nome' => 'item 04']
        ]);
    }
}
