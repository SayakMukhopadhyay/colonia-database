<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\System;
use App\Models\Faction;
use App\Models\Station;
use App\Models\History;
use App\Models\Influence;

class BaseController extends Controller
{
    public function index() {

        $history = History::with('location', 'location.economy', 'faction', 'faction.government')
            ->where('date', '>=', Carbon::yesterday()->format("Y-m-d"))
            ->orderBy('date', 'desc')->get();

        $influences = Influence::with('system', 'system.stations', 'system.economy', 'faction', 'faction.government', 'state')
            ->where('current', 1)
            ->get();
        $important = $influences->filter(function ($value, $key) {
            if (!$value->system || !$value->system->inhabited()) {
                return false; // safety for bad data
            }
            $states = ['Boom', 'Investment', 'None'];
            // ignore uninteresting states
            if (in_array($value->state->name, $states)) {
                return false;
            }
            $states = ['War', 'Election'];
            if (!in_array($value->state->name, $states)) {
                if ($value->system->controllingFaction()->id != $value->faction->id) {
                    return false; // ignore most states for non-controlling factions
                }
            }
            return true;
        });

        $systems = System::with('phase', 'economy')->orderBy('name')->get();
        $factions = Faction::with('government')->orderBy('name')->get();
        $stations = Station::with('economy', 'stationclass')->orderBy('name')->get();

        $population = System::sum('population');

        $iconmap = [];
        $economies = [];
        foreach ($stations as $station) {
            if ($station->stationclass->hasSmall) {
                if (!isset($economies[$station->economy->name])) {
                    $economies[$station->economy->name] = 0;
                }
                $economies[$station->economy->name]++;
                $iconmap[$station->economy->name] = $station->economy->icon;
            }
        }

        $governments = [];
        foreach ($factions as $faction) {
            if (!isset($governments[$faction->government->name])) {
                $governments[$faction->government->name] = 0;
            }
            $governments[$faction->government->name]++;
            $iconmap[$faction->government->name] = $faction->government->icon;
        }
        arsort($governments);
        
        return view('index', [
            'population' => $population,
            'populated' => $systems->filter(function($v) { return $v->population > 0; })->count(),
            'unpopulated' => $systems->filter(function($v) { return $v->population == 0; })->count(),
            'dockables' => $stations->filter(function($v) { return $v->stationclass->hasSmall; })->count(),
            'players' => $factions->filter(function($v) { return $v->player; })->count(),
            'economies' => $economies,
            'governments' => $governments,
            'systems' => $systems,
            'factions' => $factions,
            'stations' => $stations,
            'historys' => $history,
            'importants' => $important,
            'fakeglobals' => ['Retreat', 'Expansion'],
            'iconmap' => $iconmap
        ]);
    }
//
    public function progress() {
        $user = \Auth::user();
        if (!$user) {
            \App::abort(403);
        }

        if ($user->rank == 0) {
            return view('progressno');
        }

        $today = Carbon::now();
        $target = \App\Util::tick();
        $influenceupdate = System::where('population', '>', 0)
            ->whereDoesntHave('influences', function($q) use ($target) {
                $q->where('date', $target->format("Y-m-d 00:00:00"));
            })->orderBy('catalogue')->get();

        $reportsupdate = System::where('population', '>', 0)
            ->whereDoesntHave('systemreports', function($q) use ($today) {
                $q->where('date', $today->format("Y-m-d 00:00:00"));
            })->orderBy('catalogue')->get();

        $pendingupdate = [];
        $factions = Faction::with('states')->orderBy('name')->get();
        foreach ($factions as $faction) {
            if ($faction->states->count() > 0 &&
            $target->isSameDay(new Carbon($faction->states[0]->pivot->date))) {
                // pending states up to date
            } else {
                $pendingupdate[] = $faction;
            }
        }
        
        return view('progress', [
            'target' => $target,
            'today' => $today,
            'userrank' => $user->rank, // TODO: Composer
            'influenceupdate' => $influenceupdate,
            'reportsupdate' => $reportsupdate,
            'pendingupdate' => $pendingupdate,
        ]);
    }
}
