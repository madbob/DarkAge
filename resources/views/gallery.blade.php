@extends('app')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h3>
				This is the gallery of photos shoted by {{ $set->subscriber->username }}.
			</h3>
			<p>
				Click on all of them you like more; within <b>{{ floor((((strtotime($set->created_at) + (60 * 60 * 24 * 15) - time()) / 60) / 60) / 24) }} days</b>, the <b>top 5</b> will be selected and printed by the author to preserve his memories. <a href="{{ url('/') }}">Read more about.</a>
			</p>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="pull-right">
				<h5>Share this page</h5>
				@include('social', ['set' => $set])
			</div>
		</div>
	</div>

	<br/>

	<div class="gallery">
		@foreach($set->photos as $photo)
		<div class="wrap">
			<div class="cell"><img id="{{ $photo->id }}" data-src="{{ $photo->preview }}" src="{{ url('img/placeholder.png') }}"></div>
		</div>
		@endforeach
	</div>

	<br/>

	<div class="row">
		<div class="col-md-12">
			<h3>
				Share this page on
				@include('social', ['set' => $set])
			</h3>
		</div>
	</div>
</div>

<script type="text/javascript">

$(document).ready(function() {
	$('.gallery img').unveil(400);

	$('.gallery img').load(function() {
		minigrid('.gallery', '.wrap', 0);
	});

	$('.gallery img').click(function() {
		var selected = $(this).hasClass('selected');

		if (selected == false) {
			$.get('{{ url('vote') }}/' + $(this).attr('id'));
			$(this).addClass('selected');
		}
		else {
			$.get('{{ url('unvote') }}/' + $(this).attr('id'));
			$(this).removeClass('selected');
		}
	});
});

</script>

@endsection
