<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
	<div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
		<a class="navbar-brand brand-logo" href="{{$logo_url}}" title="{{$logo_title}}">
			{{ HTML::image($host_logo, $logo_title) }}
		</a>
		<a class="navbar-brand brand-logo-mini" href="{{$mini_logo_url}}" title="{{$mini_logo_title}}">
			{{ HTML::image($host_mini_logo, $mini_logo_title) }}
		</a>
	</div>
	<div class="navbar-menu-wrapper d-flex align-items-center">
		{!! Menu::render('top-menu') !!}
		<button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
			<span class="mdi mdi-menu"></span>
		</button>
	</div>
</nav>