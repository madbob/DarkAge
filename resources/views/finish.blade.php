@extends('app')

@section('content')

<?php $current_url = url($set->subscriber->username . '/' . $set->year) ?>

<div class="container text-center">
	<div class="row">
		<div class="col-md-12">
			<h3>
				Here the top photos shoted by {{ $set->subscriber->username }} in {{ $set->year }}.
			</h3>
		</div>
	</div>

	@foreach($set->topselected as $photo)
	<div class="row">
		<div class="col-md-6 text-right">
			<img src="{{ $photo->preview }}">
		</div>
		<div class="col-md-6 text-left">
			<h4>{{ $photo->votes }} <?php $photo->votes == 1 ? print 'vote' : print 'votes' ?></h4>
		</div>
	</div>
	@endforeach

	<div class="row">
		<div class="col-md-12">
			<h2><a href="{{ url('download/' . $set->id) }}">Click here to download them, and go printing to the nearest photography shop!</a></h2>
			<h3>Digital Dark Age is coming, be quick!</h3>
		</div>
	</div>
</div>

@endsection
