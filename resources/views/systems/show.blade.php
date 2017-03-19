@extends('layout/layout')

@section('title')
{{$system->displayName()}}
@endsection

@section('content')

<div class='row'>
  <div class='col-sm-12'>
	<p>{{$system->catalogue}}</p>
	<p>Economy: {{$system->economy->name}}</p>
	<p>Population: {{$system->population}}</p>
  </div>
</div>

<div class='row'>
  <div class='col-sm-6'>
	<h2>Stations</h2>
	<table class='table table-bordered datatable'>
	  <thead>
		<tr><th>Name</th><th>Planet</th><th>Type</th></tr>
	  </thead>
	  <tbody>
	  </tbody>
	</table>
  </div>
  <div class='col-sm-6'>
	<h2>Factions</h2>
	<table class='table table-bordered datatable'>
	  <thead>
		<tr><th>Name</th><th>Influence</th><th>State</th></tr>
	  </thead>
	  <tbody>
	  </tbody>
	</table>
  </div>
</div>

<div class='row'>
  <div class='col-sm-6'>
	<h2>Distances</h2>
	<table class='table table-bordered datatable' data-order='[[1, "asc"]]'>
	  <thead>
		<tr><th>Name</th><th>Distance (LY)</th></tr>
	  </thead>
	  <tbody>
		@foreach ($others as $other)
		<tr class="{{$other->inhabited() ? 'inhabited-system' : 'uninhabited-system'}}">
		  <td><a href="{{route('systems.show', $other->id)}}">{{$other->displayName()}}</a></td>
		  <td>{{$system->distanceTo($other)}}</td>
		</tr>
		@endforeach
	  </tbody>
	</table>
	  
  </div>
</div>

@endsection
