@extends('admin.layouts.app')
@section('content')
@section('title', __('message.subscriptions'))
<!-- Main -->
<main class="main-content notificationsPage ">
    <div class="adminPageContent">
        <!-- admin page title -->
        <section class="adminPageTitle">
            <div class="adminPageTitle_left">
                <h1 class="font-nbd h22">Notifications</h1>
            </div>
        </section>

        <!-- notifications listing -->
        <section class="notificationsList" id="notificationsList">
        </section>
    </div>
    
</main>
@endsection
<script src="{{ url('assets/js/jquery.min.js') }}"></script>
<script>
function getNotificationList(page='') {
    if(page) {
        var url = page;
    } else {
        var url = "{{ route('admin/notification-list') }}";
    }

    $.ajax({
        type: "GET",
        url: url,
        success: function (response) {
            $("#notificationsList").html(response);
        }
    });
}

getNotificationList();

$(document).on('click', '.pagination a', function (e) {
    getNotificationList($(this).attr('href'));
    e.preventDefault();
});
</script>
