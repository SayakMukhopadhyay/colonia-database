@extends('layout/layout')

@section('title', 'Colonia Region System Database')

@section('content')

	<h2>Current Events</h2>
<ul id='major-events'>
  @foreach ($importants as $important)
  <li>
	@include($important->faction->government->icon)
	<a href='{{route('factions.show', $important->faction->id)}}'>
	  {{$important->faction->name}}
	</a>
	in
	@include($important->state->icon)
	{{$important->state->name}}
	@if (!in_array($important->state->name, $fakeglobals))
	in
	@include($important->system->economy->icon)
	<a href='{{route('systems.show', $important->system->id)}}'>
	  {{$important->system->displayName()}}
	</a>
	@endif
  </li>
  @endforeach
  @foreach ($historys as $history)
  <li>
	@include($history->faction->government->icon)
	<a href='{{route('factions.show', $history->faction->id)}}'>
	  {{$history->faction->name}}
	</a>
	@if ($history->location_type == 'App\Models\System')
	@if ($history->expansion)
	expanded to
	@else
	retreated from
	@endif
	@include($history->location->economy->icon)
	<a href='{{route('systems.show', $history->location->id)}}'>
	  {{$history->location->displayName()}}
	</a>
	@elseif ($history->location_type == 'App\Models\Station')
	@if ($history->expansion)
	took control of
	@else
	lost control of
	@endif
	@include($history->location->economy->icon)
	<a href='{{route('stations.show', $history->location->id)}}'>
	  {{$history->location->name}}
	</a>
	@endif 
	
  </li>
  @endforeach
</ul>

<div class='row'>
  <div class='col-sm-6'>
	<h2>Economies</h2>
	<ul id='economyflow'>
	  <li>
		@include('components/economy', ['economy' => 'Extraction'])
		<br>@include('components/economy', ['economy' => 'Refinery'])
	  </li>
	  <li>
		&#x27a0; @include('components/economy', ['economy' => 'Industrial'])
		<br>&#x27a0; @include('components/economy', ['economy' => 'High-Tech'])
	  </li>
	  <li>
		&#x27a0; @include('components/economy', ['economy' => 'Agricultural'])
		<br>&#x27a0; @include('components/economy', ['economy' => 'Service'])
		<br>&#x27a0; @include('components/economy', ['economy' => 'Military'])
	  </li>
	  <li>
		&#x27a0; @include('components/economy', ['economy' => 'Tourism'])
		<br>&#x27a0; @include('components/economy', ['economy' => 'Colony'])
	  </li>
	</ul>
	  
  </div>
  <div class='col-sm-6'>
	<h2>Governments</h2>
	<ul class='compact2'>
	  @foreach ($governments as $type => $count)
	  <li>
		{{$count}}
		@include ($iconmap[$type])
		{{$type}}
	  </li>
	  @endforeach
	</ul>
  </div>
</div>  

<div class='row'>
  <div class='col-sm-6'>
	<h2>Key Figures</h2>
	<ul>
	  <li>{{$populated}} systems supporting {{number_format($population)}} people, with {{$unpopulated}} more currently planned.</li>
	  <li>{{$dockables}} surface and orbital stations (and {{$stations->count()-$dockables}} settlements)</li>
	  <li>{{$factions->count()}} factions, of which {{$players}} came through the Colonia Expansion Initiative</li>
	</ul>
  </div>
  <div class='col-sm-6'>
	<h2>Find out more</h2>
	<ul>
	  <li><a href="{{route('stations.index')}}#cartographics">Where can I sell exploration data?</a></li>
	  <li><a href="{{route('systems.index')}}#&quot;metallic ring&quot;">Where are the pristine metallic rings?</a></li>
      <li><a href="{{route('missions.index')}}">How do missions affect the situation?</a></li>
	  <li><a href="{{route('factions.index')}}#Colonia">Which factions are named after Colonia?</a></li>
	  <li><a href="{{route('systems.index')}}#compromised">Whose nav beacons have been compromised?</a></li>
	  <li><a href="{{route('stations.index')}}#anarchy">Which stations are controlled by criminals?</a></li>
	  <li><a href="{{route('map')}}#XZ~F:Jaques~S:Colonia~P~1">Where are the best drinks?</a></li>
	</ul>
  </div>
</div>
    

@endsection
