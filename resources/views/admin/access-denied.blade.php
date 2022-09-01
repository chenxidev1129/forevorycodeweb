@extends('admin.layouts.app')
@section('content')
@section('title', 'Access Denied')
<!-- Main -->
<main class="main-content accessDeniedPage">
    <section class="accessDeniedPage_content">
        <div class="container text-center">
            <img src="{{ url('assets/admin/images/403.jpg') }}" class="img-fluid" alt="Access Denied">
            <h1 class="font-bd">Access Denied</h1>
            <p class="mb-0">The page you're trying to access has restricted access. <br class="d-none d-sm-block"> please refer to your system administrator</p>
        </div>
    </section>       
</main>
@endsection


