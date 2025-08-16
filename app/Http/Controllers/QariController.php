<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Qari;

class QariController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $qaris = Qari::orderBy('is_featured', 'desc')
                    ->orderBy('name')
                    ->get();
                    
        return response()->json($qaris);
    }
}