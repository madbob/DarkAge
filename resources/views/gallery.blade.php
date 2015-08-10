@extends('app')

@section('content')

<?php $current_url = url($set->subscriber->username . '/' . $set->year) ?>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h3>
				This is the gallery of photos shoted by {{ $set->subscriber->username }} in {{ $set->year }}.
			</h3>
			<p>
				Click on all of them you like more; within <b>{{ floor((((strtotime($set->created_at) + (60 * 60 * 24 * 15) - time()) / 60) / 60) / 24) }} days</b>, the <b>top 5</b> will be selected and printed by the author to preserve the memories of the year. <a href="{{ url('/') }}">Read more about.</a>
			</p>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="pull-right">
				<h5>Share this page</h5>
				<a href="https://twitter.com/share?url={{ rawurlencode($current_url) }}&text=help%20to%20choose%20the%20best%20photos%20of%20{{ $set->year }}%20to%20print%20and%20preserve" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" alt="Share on Twitter"><img width="50px" src="{{ url('img/twitter.png') }}" /></a>
				<a href="http://www.facebook.com/sharer.php?u={{ rawurlencode($current_url) }}" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" alt="Share on Facebook"><img width="50px" src="{{ url('img/facebook.png') }}" /></a>
				<a href="https://plus.google.com/share?url={{ rawurlencode($current_url) }}" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=350,width=480');return false;" target="_blank" alt="Share on Google+"><img width="50px" src="{{ url('img/gplus.png') }}" /></a>
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
				<a href="https://twitter.com/share?url={{ rawurlencode($current_url) }}&text=help%20to%20choose%20the%20best%20photos%20of%20{{ $set->year }}%20to%20print%20and%20preserve" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" alt="Share on Twitter"><img src="{{ url('img/twitter.png') }}" /></a>
				<a href="http://www.facebook.com/sharer.php?u={{ rawurlencode($current_url) }}" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" alt="Share on Facebook"><img src="{{ url('img/facebook.png') }}" /></a>
				<a href="https://plus.google.com/share?url={{ rawurlencode($current_url) }}" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=350,width=480');return false;" target="_blank" alt="Share on Google+"><img src="{{ url('img/gplus.png') }}" /></a>
			</h3>
		</div>
	</div>
</div>

<script type="text/javascript">

$(document).ready(function() {
	$('.gallery img').unveil(400, function() {
		minigrid('.gallery', '.wrap', 0);
	});

	minigrid('.gallery', '.wrap', 0);

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
