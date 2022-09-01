<!DOCTYPE html>
<html lang="en">

<head>
    <title> Forgot Password || Forevory</title>
    @include('user.layouts.header-links')	
</head>

<body >

    <!-- Main -->
    <main class="main-content loginPage">

        <section class="loginWrap">
        	<div class="loginWrap_outer">
                <div class="loginWrap_logo text-center">
				    <img src="{{ url('assets/images/logo.svg')}}" alt="logo">
                </div>
                <div class="bg-white loginWrap_inner">
            		<div class="text-center">
            			<h1 class="h34 font-nbd">@lang('message.we_have_sent_you_email')</h1>
						<p class="text-left h15">Enter the 6 digit verification code sent to g*****@gmail.com <a href="{{ route('forgot-password') }}" class="theme-link font-bd"> Change </a></p>
            		</div>
            		<form action="reset-password.php">
            			<div class="form-group">
            				<input type="number" class="form-control" placeholder="6 digit code" maxlength="6">
							<a href="javascript:void(0)" class=" mt-1 h15 theme-link font-bd d-block">
                                @lang('message.resend_title')
                            </a>
            			</div>
            			<div class="form-group">
    	        			<button type="submit" class="btn btn-primary ripple-effect w-100">@lang('message.submit_title')</button>
    	        		</div>

            		</form>
                </div>
        	</div>
        </section>
        
    </main>

    <!-- Header -->
	@include('user.layouts.footer-links')
   
</body>

</html>