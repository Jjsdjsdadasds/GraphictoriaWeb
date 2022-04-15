@php
	// TODO: load from website configuration?
    $routes = [
		[
			"label" => "Games",
			"location" => "/games"
		],
		[
			"label" => "Shop",
			"location" => "/shop"
		],
		[
			"label" => "Forum",
			"location" => "/forum"
		]
	]
@endphp

<div class="navbar graphictoria-navbar fixed-top navbar-expand-md shadow-sm">
	<div class="container-md">
		<a class="navbar-brand" href="/">
			<img src="{{ asset('/images/logo.png') }}" alt="Graphictoria" width="43" height="43" draggable="false"/>
		</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#graphictoria-nav" aria-controls="graphictoria-nav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="graphictoria-nav">
			<ul class="navbar-nav me-auto">
				@foreach($routes as $route)
					@php
						// HACK
						$route = (object)$route;
					@endphp
					<li class="nav-item">
						<a class="nav-link" href="{{ $route->location }}">{{ $route->label }}</a>
					</li>
				@endforeach
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="graphictoria-nav-dropdown" role="button" data-bs-toggle="dropdown" area-expanded="false">More</a>
					<ul class="dropdown-menu graphictoria-nav-dropdown" area-labelledby="graphictoria-nav-dropdown">
						<li><a class="dropdown-item" href="/users">Users</a></li>
						<li><a class="dropdown-item" href="https://discord.gg/q666a2sF6d" target="_blank" rel="noreferrer">Discord</a></li>
					</ul>
				</li>
			</ul>
			@if($authenticated)
				<div class="flex">
					<p class="my-auto me-2 text-muted" style="color:#e59800!important;font-weight:bold">
						<span data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tokens are Graphictoria's currency.">
							<img src="{{ asset('images/symbols/token.svg') }}" height="20" width="20" class="img-fluid me-1" style="margin-top:-1px" />
							123
						</span>
					</p>
					<div class="dropdown">
						<a class="nav-link dropdown-toggle graphictoria-user-dropdown" href="#" id="graphictoria-user-dropdown" role="button" data-bs-toggle="dropdown" area-expanded="false">
							<span class="d-flex align-items-center">
								<img src="{{ asset('images/testing/headshot.png') }}" class="img-fluid border me-1 graphictora-user-circle" width="37" height="37">
								<p>Username</p>
							</span>
						</a>
						<ul class="dropdown-menu graphictoria-user-dropdown" area-labelledby="graphictoria-user-dropdown">
							<li><a class="dropdown-item" href="{{ url('/my/settings') }}">Settings</a></li>
							<li><a class="dropdown-item" href="{{ url('/my/logout') }}">Logout</a></li>
						</ul>
					</div>
				</div>
			@else
				<a class="btn btn-success" href="/login">Login / Sign up</a>
			@endif
		</div>
	</div>
</div>
<div class="graphictoria-nav-margin"></div>