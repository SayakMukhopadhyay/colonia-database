<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\System;
use App\Models\Faction;
use App\Models\Facility;
use App\Models\Influences;

class MapController extends Controller
{
    
    public function index(Request $request) {
        $systems = System::with('phase', 'stations', 'stations.faction', 'facilities')->orderBy('name')->orderBy('catalogue')->get();

        $projection = 'XZ';
        if ($request->input('projection') == "XY") {
            $projection = 'XY';
        } else if ($request->input('projection') == "ZY") {
            $projection = 'ZY';
        }
        return view('map/index', [
            'systems' => $systems,
            'projection' => $projection,
            'factions' => Faction::orderBy('name')->get(),
            'facilities' => Facility::where('type', 'System')->orderBy('name')->get()
        ]);
    }

    
}
