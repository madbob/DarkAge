@extends('app')

@section('content')

<div class="container flow-text home">
	<div class="row">
		<div class="col-md-12">
			<blockquote cite="https://en.wikipedia.org/wiki/Digital_dark_age" class="lead">
				The <b>digital dark age</b> is a possible future situation where it will be difficult or impossible to read historical electronic documents and multimedia, because they have been recorded in an obsolete and obscure file format. The name derives from the term <i>Dark Ages</i> in the sense that there would be a relative lack of written record.
				<span>from Wikipedia</span>
			</blockquote>
		</div>
	</div>

	<br />

	<div class="row">
		<div class="col-md-12">
			<p>
				We need to fight back the incoming digital dark age, by moving some information from rotting and unstable bits to a more durable phisical form.
			</p>
			<p>
				Starting with the most popular kind of information: <b>photos</b>. Due digital photography we all collect huge amounts of shots, every day and in every condition. But most of them are never revisited, and lot of them are daily lost - forever - by failing hard disks or ephemeral web services.
			</p>
			<p>
				What about <b>print</b> some of them? This small service permits you and your friends to choose <b>five</b> photos among those you took the last year, and you are invited to print them and collect in your home, in a photo album, framed on the wall or wherever you like.
			</p>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<ol type="I">
				<li>Load photos from your Flickr photostream</li>
				<li>Share the given link on the social networks</li>
				<li>Within two weeks, the poll is closed</li>
				<li>Download the top five photos</li>
				<li>Go print them (maybe asking to the nearest photography shop)</li>
				<li>Don't forget to remember...</li>
			</ol>
		</div>
	</div>

	<hr/>

	<div class="row">
		<form class="form-horizontal" method="POST" action="{{ url('/startup') }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="year" value="{{ date('Y') - 1 }}">

			<div class="col-md-9 form-group form-group-lg">
				<input class="form-control" type="url" name="url" required="required" placeholder="Paste here URL of your Flickr photostream, e.g. https://www.flickr.com/photos/your_username/" autocomplete="off">
			</div>
			<div class="col-md-3">
				<button class="btn btn-primary btn-lg pull-right" type="submit" name="action">Take Action Now</button>
			</div>
		</form>
	</div>
</div>

@endsection
