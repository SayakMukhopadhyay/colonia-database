@extends('layout/layout')

@section('title')
{{$station->name}}
@endsection

@section('content')

@if ($userrank > 0)
<a class='edit' href='{{route('stations.edit', $station->id)}}'>Update</a>
@endif


<table class='table table-bordered'>
  <tr>
	<th>Type</th>
	<td>{{$station->stationclass->name}}</td>
  </tr>
  <tr>
	<th>Location</th>
	<td><a href='{{route("systems.show", $station->system->id)}}'>{{$station->system->displayName()}}</a> {{$station->planet}}</td>
  </tr>
  <tr>
	<th>Distance (Ls)</th>
	<td>{{$station->distance}}</td>
  </tr>
  <tr>
	<th>Docking Pads</th>
	<td>
	  @if ($station->stationclass->hasSmall) Small @endif
	  @if ($station->stationclass->hasMedium) Medium @endif
	  @if ($station->stationclass->hasLarge) Large @endif
	</td>
  </tr>
  <tr>
	<th>Facilities</th>
	<td>
	  @foreach ($station->facilities->sortBy('name') as $facility)
	  @if (!$facility->pivot->enabled)<span class='facility-disabled'>@endif
	  @include ($facility->icon)
	  {{$facility->name}}
	  @if (!$facility->pivot->enabled)</span>@endif
	  <br>
	  @endforeach
	</td>
  </tr>
  <tr>
	<th>Economy</th>
	<td>
	  @include($station->economy->icon)
	  {{$station->economy->name}}
	</td>

  </tr>
  <tr>
	<th>Controlling Faction</th>
	<td>
	  @include($station->faction->government->icon)
	  <a href='{{route('factions.show', $station->faction->id)}}'>{{$station->faction->name}}</a>
	</td>

  </tr>
  <tr>
    <th>Primary?</th>
	<td>
	  @if ($station->primary)
	  @include('layout/yes')
	  @else
	  @include('layout/no')
	  @endif
	</td>
  </tr>
</table>

@if ($station->eddb)
<p><a href='https://eddb.io/station/{{$station->eddb}}'>EDDB Record</a></p>
@endif
    

@endsection
