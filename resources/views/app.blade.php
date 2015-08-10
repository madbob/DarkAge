<!DOCTYPE html>
<html>
	<head>
		<title>Dark Age</title>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" href="{{ url('css/style.css') }}">

		<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="{{ url('js/jquery.unveil.js') }}"></script>
		<script type="text/javascript" src="{{ url('js/minigrid.min.js') }}"></script>

		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	</head>

	<body>
		<br />

		@if(Session::has('message'))
		<div class="alert alert-danger">
			{{ Session::get('message') }}
		</div>
		@endif

		@yield('content')

		<br />

		<nav class="navbar navbar-default navbar-fixed-bottom page-footer hidden-xs">
			<div class="container">
				<div class="row">
					<div class="col-md-9">
						<h3 class="white-text"><a class="white-text" href="{{ url('/') }}">Dark Age</a></h3>
						<p class="grey-text text-lighten-4">Created and hosted by <a class="white-text" href="http://madbob.org/">Roberto -MadBob- Guido</a></p>
					</div>
					<div class="col-md-3">
						<p></p>
						<ul>
							<li><a class="grey-text text-lighten-3" href="#!">view on GitHub</a></li>
							<li><a class="grey-text text-lighten-3" href="https://www.flickr.com/photos/robertoguido/">me on Flickr</a></li>
						</ul>
					</div>
				</div>

				<br/>
			</div>
		</nav>

	</body>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</html>
