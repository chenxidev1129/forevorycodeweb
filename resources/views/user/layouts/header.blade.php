<header class="header" id="topMenu">
    <nav class="navbar p-0">
    	<div class="container">
	 		<a href="{{ url('/') }}" class="navbar-brand p-0">
	 			<img src="{{ url('assets/images/logo.svg') }}" class="img-fluid d-none d-md-block" alt="Forevory">
	 			<img src="{{ url('assets/images/mobile-logo.svg') }}" class="img-fluid d-md-none" alt="Forevory">
			</a>
			@if(Auth::guard(request()->guard)->check())
			<div class="dropdown notificationsMenu ml-auto">
				<a onclick="loadNotifications()" class="dropdown-toggle" href="#" role="button" id="notificationsMenu" data-toggle="dropdown" aria-expanded="false">
					<em class="icon-notification-outline"></em>
				</a>

				<div class="dropdown-menu dropdown-menu-right border-0" aria-labelledby="notificationsMenu">
					<h4 class="font-sm">Notifications</h4>
					<div class="dropdown-menu-inner">
						<div id="loadNotificationWindow">
						</div>
						<div id="loadMoreNotification" class="text-center d-none">
							<button onclick="loadMore()" type="button" id="loadMoreBtn" class="btn btn-outline-primary ripple-effect btn-sm">Load More</button>
						</div>
					</div>
				</div>
			</div>

	 		<div class="header_right d-flex align-items-center justify-content-end">
	 			<div class="dropdown">
		 			<button class="navbar-toggler dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
	                    <span class="line"></span>
	                    <span class="line"></span>
	                    <span class="line"></span>
	                </button>
	                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
					    <a class="dropdown-item" href="{{ route('profile') }}">Profiles</a>
					    <a class="dropdown-item" href="{{ route('edit-account') }}">Edit Account</a>
					    <a class="dropdown-item" href="{{ route('transactions') }}">Transactions</a>
						@if(getUserDetail()->login_type == 'forevory')
							<a class="dropdown-item" href="{{ route('change-password') }}">Change Password</a>
						@endif
					    <a class="dropdown-item" href="{{ route('logout') }}">Log Out</a>
					  </div>
				</div>
                <div class="profile overflow-hidden rounded-circle">
					@if(getUserDetail()->image_url)
						<img src="{{ getUserDetail()->image_url }}" alt="Profile Img">	
					@else
					<img src="{{ url('assets/images/user-default.jpg') }}" alt="Profile Img">
					@endif
                </div>
	 		</div>
			@endif
	 	</div>
	</nav>
</header>
  