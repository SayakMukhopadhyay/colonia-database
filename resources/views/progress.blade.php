@extends('layout/layout')

@section('title', 'Update Progress')

@section('content')

<p>Updates for tick {{\App\Util::displayDate($target) }} / day {{\App\Util::displayDate($today) }}</p>

@if (\App\Util::nearTick())

<p class='alert alert-danger'><strong>Warning:</strong> The tick is
expected to have occurred very recently. Use caution when updating
influence and state information especially if there appears to be no
visible change.</p>

@endif
        

<p>The easiest things to update are listed first. Numbers after each item indicate the days since the last update.</p>
  
<h2>Systems needing influence update</h2>
@if (count($influenceupdate) > 0)
@if($userrank > 0)
<p>The following systems do not have influence updates on todays tick. Please ensure before starting that the tick is complete. Collect influence data from the system map only for accuracy - this does not require you to be in the system.</p>
@endif
<p>You can update this data without needing to log in by entering the system while running an EDDN-connected application (e.g. EDDiscovery, ED Market Connector or EDDI). Systems where the influence has not changed since yesterday cannot be updated by this route until at least four hours have passed since the tick.</p>
<ul class='compact'>
  @foreach ($influenceupdate as $system)
  <li>
	@if($userrank > 0)
	<a href="{{route('systems.edit',$system->id)}}">{{$system->displayName()}}</a>
	@else
	<a href="{{route('systems.show',$system->id)}}">{{$system->displayName()}}</a>
	@endif
	@include('progressage', ['date' => \App\Util::age($system->influences()->max('date'))])
  </li>
  @endforeach
</ul>
@else
<p><strong>All systems updated!</strong></p>
@endif

<h2>Factions needing pending state updates</h2>
@if (count($pendingupdate) > 0)
@if($userrank > 0)
<p>The following factions do not have pending state updates today. You will need to enter the system to view the pending states in the right panel.</p>
@endif
<p>You can update this data without needing to log in by entering a system where the faction is present while running an EDDN-connected application (e.g. EDDiscovery, ED Market Connector or EDDI).</p>
<p>For a comprehensive survey, it is best to start by doing the Core outposts - as these have many factions only present there - then go to hub systems such as Dubbuennel where many CEI factions are present. It is not necessary to update every faction every day as pending states usually change slowly.</p>
<ul class='compact'>
  @foreach ($pendingupdate as $faction)
  <li>
	@if($userrank > 0)
	<a href="{{route('factions.edit',$faction->id)}}">{{$faction->name}}</a>
	@else
	<a href="{{route('factions.show',$faction->id)}}">{{$faction->name}}</a>
	@endif
	@include('progressage', ['date' => \App\Util::age($faction->states->count() > 0 ? $faction->states[0]->pivot->date : null)])
  </li>
  @endforeach
</ul>
@else
<p><strong>All factions updated!</strong></p>
@endif



<h2>Systems needing report updates</h2>
@if (count($reportsupdate) > 0)
<p>The following systems do not have report updates today. You will need to dock at a station in the system to view traffic, crime and bounty reports in the local Galnet. This does not need daily updates for everywhere!</p>
@if($userrank == 0)
<p>This data can only be updated by logging in and entering it manually.</p>
@endif
<ul class='compact'>
  @foreach ($reportsupdate as $system)
  <li>
	@if($userrank > 0)
	<a href="{{route('systems.editreport',$system->id)}}">{{$system->displayName()}}</a>
	@else
	<a href="{{route('systems.show',$system->id)}}">{{$system->displayName()}}</a>
	@endif
	@include('progressage', ['date' => \App\Util::age($system->systemreports()->max('date'))])
  </li>
  @endforeach
</ul>
@else
<p><strong>All systems updated!</strong></p>
@endif


@endsection
