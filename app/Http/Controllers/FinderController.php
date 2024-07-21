<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Place;

class FinderController extends Controller
{
    public function result(Place $place) {
        return view('finder.result')->with(['places' => $place->get()]);
        // return view('finder.result');
    }
    
}
