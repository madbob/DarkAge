@extends('app')

@section('content')

<div class="container flow-text">
	<div class="row">
		<div class="col-md-12">
			<h2>We are indexing your photos... </h2>
			<h3>Please wait and don't leave this page.</h3>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="progress progress-striped active">
				<div class="progress-bar"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
				</div>
			</div>
		</div>
	</div>

	<br />

	<div class="row">
		<div class="col-md-12">
			@if(count($sets) > 0)
			<p>
				Meanwhile, what about get a look to other people recent galleries? (Links open in a new tab)
			</p>
			<ul>
				@foreach($sets as $set)
				<li><a href="{{ $set->url() }}" target="_blank">{{ $set->subscriber->username }}</a></li>
				@endforeach
			</ul>
			@else
			<p>
				Meanwhile, read more <a href="https://en.wikipedia.org/wiki/Digital_dark_age" target="_blank">about Digital Dark Age on Wikipedia</a> (link opens in a new tab)
			</p>
			@endif
		</div>
	</div>
</div>

<script type="text/javascript">

$(document).ready(function() {
	$(document).ajaxError(function() {
		window.location = "{{ url('/') }}";
	});

	$.post('/init', {set: {{ $current_set->id }}, _token: "{{ csrf_token() }}"}, function(data) {
		if (data == 'ko')
			window.location = "{{ url('/') }}";
		else
			window.location = "{{ $current_set->url() }}";
	});
});

</script>

@endsection
