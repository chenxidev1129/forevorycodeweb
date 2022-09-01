<header class="adminHeader" id="adminMenu">
    <nav class="navbar p-0 navbar-expand-md navbar-light ">
        <a href="{{ route('admin/dashboard') }}" class="navbar-brand p-0">
            <img src="{{ url('assets/images/logo.svg') }}" class="img-fluid d-none d-md-block" alt="Forevory">
            <img src="{{ url('assets/images/mobile-logo.svg') }}" class="img-fluid d-md-none" alt="Forevory">
        </a>
		<ul class="navbar-nav nav-right align-items-center ml-auto order-md-1">
			<li class="nav-item notifications">
				<div class="dropdown">
					<a onclick="loadNotifications()" class="nav-link dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-expanded="false" href="javascript:void(0);">
						<em class="icon-notification-outline"></em>
					</a>

					<div class="dropdown-menu dropdown-menu-right list-unstyled border-0" aria-labelledby="dropdownMenuLink">
						<h4 class="font-sm">Notifications</h4>
						<div class="dropdown-menu-inner position-relative">
							<div id="loadNotificationWindow">
							</div>
							<div id="loadMoreNotification" class="text-center d-none">
								<button onclick="loadMore()" type="button" id="loadMoreBtn" class="btn btn-outline-primary ripple-effect btn-sm">Load More</button>
							</div>
						</div>
					</div>
				</div>
			</li>
			<li class="nav-item">
				<a class="nav-link {{(Request::segment(2) == 'logout') ? 'active' : ''}}" href="{{ route('admin/logout') }}">Logout</a>
			</li>
		</ul>
	    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#adminNavigation" aria-controls="adminNavigation" aria-expanded="false" aria-label="Toggle navigation">
	    	<span class="navbar-toggler-icon"></span>
	  	</button>
	  	<div class="collapse navbar-collapse" id="adminNavigation">
	        <ul class="navbar-nav ml-auto">
	            <li class="nav-item">
	                <a class="nav-link {{(Request::segment(2) == 'dashboard') ? 'active' : ''}}"  href="{{ route('admin/dashboard')}}">Dashboard <span class="sr-only">(current)</span></a>
	            </li>
	            <li class="nav-item">
	                <a class="nav-link {{(Request::segment(2) == 'accounts' || Request::segment(2) == 'profile-details' ) ? 'active' : ''}}"  href="{{ route('admin/accounts') }}">Accounts</a>
	            </li>
	            <li class="nav-item">
	                <a class="nav-link {{(Request::segment(2) == 'transactions') ? 'active' : ''}}" href="{{ route('admin/transactions') }}">Transactions</a>
	            </li>
	            <li class="nav-item">
	                <a class="nav-link {{(Request::segment(2) == 'access') ? 'active' : ''}}" href="{{ url('admin/access') }}">Access</a>
	            </li>
	            <li class="nav-item">
	                <a class="nav-link {{(Request::segment(2) == 'subscriptions') ? 'active' : ''}}" href="{{ url('admin/subscriptions') }}">Subscriptions</a>
	            </li>
	            <li class="nav-item">
	                <a class="nav-link {{(Request::segment(2) == 'change-password') ? 'active' : ''}}" href="{{ route('admin/change-password') }}">Change Password</a>
	            </li>
	            <!-- <li class="nav-item">
	                <a class="nav-link {{(Request::segment(2) == 'logout') ? 'active' : ''}}" href="{{ route('admin/logout') }}">Logout</a>
	            </li> -->
	        </ul>
	    </div>
    </nav>
</header>