

<!-- scripts -->
<script src="{{ url('assets/js/jquery.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ url('assets/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ url('assets/js/progressively.min.js') }}"></script>
<script src="{{ url('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ url('assets/js/jquery.toast.min.js') }}"></script>
<script src="{{ url('assets/js/intlTelInput.js') }}"></script>
<!-- Laravel Javascript Validation -->
<script type="text/javascript" src="{{ url('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script src="{{ url('assets/js/app.toast.js') }}"></script>
<script src="{{ url('assets/js/mbFormat.js') }}"></script>


@if(Session::has('message'))
<script>
    var type = "{{ Session::get('alert-type', 'info') }}";
    switch (type) {
        case 'info':
            _toast.info("{{ Session::get('message') }}");
            break;
        case 'warning':
            _toast.warning("{{ Session::get('message') }}");
            break;
        case 'success':
            _toast.success("{{ Session::get('message') }}");
            break;
        case 'error':
            _toast.error("{{ Session::get('message') }}");
            break;
    }
</script>
@endif
<script>

$(document).ready(function() {
    
    //ripple-effect for button
    $('.ripple-effect, .ripple-effect-dark').on('click', function(e) {
        var rippleDiv = $('<span class="ripple-overlay">'),
            rippleOffset = $(this).offset(),
            rippleY = e.pageY - rippleOffset.top,
            rippleX = e.pageX - rippleOffset.left;
        rippleDiv.css({
            top: rippleY - (rippleDiv.height() / 2),
            left: rippleX - (rippleDiv.width() / 2),
            // background: $(this).data("ripple-color");
        }).appendTo($(this));
        window.setTimeout(function() {
            rippleDiv.remove();
        }, 800);
    });

    // progressively
    progressively.init();

    // show password text field
    $('.showPassword').click(function() {
        $(this).children('em').toggleClass('icon-eye icon-eye-off')
            $(this).siblings(".form-control").attr('type', function(index, attr){
            return attr == 'text' ? 'password' : 'text';
        });
    });


});

    getNotificationUnreadCount();

    // Read unread header notification
    function getNotificationUnreadCount(){
        $.ajax({
            type: "GET",
            url:  "{{ url('/admin/load-notifications') }}",
            data: "limit=3&page=1",
            success: function (data) {
                if(data.success){
                    if(data.total > 0){
                        $('#adminMenu .nav-right .notifications #dropdownMenuLink').addClass('received');
                    }else{
                        $('#adminMenu .nav-right .notifications #dropdownMenuLink').removeClass('received');
                    }
                }
            },
        });
    }

    /* Load notification */
    var page_num_load = 1;
    function loadNotifications() {
        $('#loadMoreNotification').addClass('d-none');
        $('#loadNotificationWindow').html('<div class="notificationLoader"><span class="btnLoader  spinner-border "></span></div>');
        
        page_num_load = 1;
        $.ajax({
            type: "GET",
            url:  "{{ url('/admin/load-notifications') }}",
            data: "limit=3&page=" + page_num_load,
            beforeSend: function() {
                $('#loadNotificationWindow').html('<div class="notificationLoader"><span class="btnLoader  spinner-border "></span></div>');
            },
            success: function (data) {
                if(data.success){
                    $("#loadNotificationWindow").html("");
                    $('#loadNotificationWindow').html(data.html);
                    if(data.loadMore == 1) {
                        $('#loadMoreNotification').removeClass('d-none');
                    }
                }else{
                    $('#loadNotificationWindow').hide();
                }
            },
        });
    }

    /* Load more notification */
    function loadMore() {
        event.stopPropagation();
        page_num_load += 1;
        $.ajax({
            type: "GET",
            url:  "{{ url('/admin/load-notifications') }}",
            data: "limit=3&page=" + page_num_load,
            success: function (data) {
                if(data.success){
                    $('#loadNotificationWindow').append(data.html);

                    if(data.loadMore == 1) {
                        $('#loadMoreNotification').removeClass('d-none');
                    } else {
                        $('#loadMoreNotification').addClass('d-none');
                    }

                }
            },
        });
    }

</script>