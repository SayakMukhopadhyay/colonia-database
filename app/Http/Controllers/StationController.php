<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\Stationclass;
use App\Models\Faction;
use App\Models\Economy;
use App\Models\System;
use Illuminate\Http\Request;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stations = Station::with('system', 'economy', 'stationclass', 'faction', 'faction.government')->get();
        //
        return view('stations/index', [
            'stations' => $stations
        ]);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = \Auth::user();
        if ($user->rank < 2) {
            \App::abort(403);
        }

        $classes = Stationclass::orderBy('name')->get();
        $factions = Faction::orderBy('name')->get();
        $economies = Economy::orderBy('name')->get();
        $systems = System::where('population', '>', 0)->orderBy('name')->get();
        
        return view('stations/create', [
            'classes' => \App\Util::selectMap($classes),
            'factions' => \App\Util::selectMap($factions),
            'economies' => \App\Util::selectMap($economies),
            'systems' => \App\Util::selectMap($systems, false, 'displayName')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = \Auth::user();
        if ($user->rank < 2) {
            \App::abort(403);
        }

        $this->validate($request, [
            'name' => 'required',
            'planet' => 'required',
            'distance' => 'required|numeric|min:1'
        ]);

        $station = new Station();
        return $this->updateModel($request, $station);
    }

    private function updateModel(Request $request, Station $station)
    {
        $station->name = $request->input('name');
        $station->system_id = $request->input('system_id');
        $station->planet = $request->input('planet');
        $station->distance = $request->input('distance');
        $station->stationclass_id = $request->input('stationclass_id');
        $station->economy_id = $request->input('economy_id');
        $station->faction_id = $request->input('faction_id');
        $station->primary = $request->input('primary', 0);
        $station->eddb = $request->input('eddb');
        $station->save();

        return redirect()->route('stations.show', $station->id);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Station  $station
     * @return \Illuminate\Http\Response
     */
    public function show(Station $station)
    {
        return view('stations/show', [
            'station' => $station
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Station  $station
     * @return \Illuminate\Http\Response
     */
    public function edit(Station $station)
    {
        $user = \Auth::user();
        if ($user->rank < 2) {
            \App::abort(403);
        }

        $classes = Stationclass::orderBy('name')->get();
        $factions = Faction::orderBy('name')->get();
        $economies = Economy::orderBy('name')->get();
        $systems = System::where('population', '>', 0)->orderBy('name')->get();
        
        return view('stations/edit', [
            'station' => $station,
            'classes' => \App\Util::selectMap($classes),
            'factions' => \App\Util::selectMap($factions),
            'economies' => \App\Util::selectMap($economies),
            'systems' => \App\Util::selectMap($systems, false, 'displayName')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Station  $station
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Station $station)
    {
        $user = \Auth::user();
        if ($user->rank < 2) {
            \App::abort(403);
        }

        $this->validate($request, [
            'name' => 'required',
            'planet' => 'required',
            'distance' => 'required|numeric|min:1'
        ]);

        return $this->updateModel($request, $station);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Station  $station
     * @return \Illuminate\Http\Response
     */
    public function destroy(Station $station)
    {
        //
    }
}