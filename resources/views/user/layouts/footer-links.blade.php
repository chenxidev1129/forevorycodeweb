

<!-- scripts -->
<script src="{{ url('assets/js/jquery.min.js') }}" crossorigin="anonymous"></script>

<script src="{{ url('assets/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ url('assets/js/progressively.min.js') }}"></script>
<script src="{{ url('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ url('assets/js/jquery.toast.min.js') }}"></script>
<script src="{{ url('assets/js/bootbox.min.js') }}"></script>
<!-- Get time zone js -->
<script src="{{ url('assets/js/moment.min.js') }}"></script>
<script src="{{ url('assets/js/moment-timezone-with-data.min.js') }}"></script>
<!-- Laravel Javascript Validation -->

<script type="text/javascript" src="{{ url('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script src="{{ url('assets/js/app.toast.js') }}"></script>
<script src="{{ url('assets/js/mbFormat.js') }}"></script>
<script src="{{ url('assets/js/cropper.js') }}"></script>
<!--fancybox js  -->
<script src="{{ url('assets/js/jquery.fancybox.min.js') }}"></script>

<script type="text/javascript">
    (function(f,b){if(!b.__SV){var e,g,i,h;window.mixpanel=b;b._i=[];b.init=function(e,f,c){function g(a,d){var b=d.split(".");2==b.length&&(a=a[b[0]],d=b[1]);a[d]=function(){a.push([d].concat(Array.prototype.slice.call(arguments,0)))}}var a=b;"undefined"!==typeof c?a=b[c]=[]:c="mixpanel";a.people=a.people||[];a.toString=function(a){var d="mixpanel";"mixpanel"!==c&&(d+="."+c);a||(d+=" (stub)");return d};a.people.toString=function(){return a.toString(1)+".people (stub)"};i="disable time_event track track_pageview track_links track_forms track_with_groups add_group set_group remove_group register register_once alias unregister identify name_tag set_config reset opt_in_tracking opt_out_tracking has_opted_in_tracking has_opted_out_tracking clear_opt_in_out_tracking start_batch_senders people.set people.set_once people.unset people.increment people.append people.union people.track_charge people.clear_charges people.delete_user people.remove".split(" ");
    for(h=0;h<i.length;h++)g(a,i[h]);var j="set set_once union unset remove delete".split(" ");a.get_group=function(){function b(c){d[c]=function(){call2_args=arguments;call2=[c].concat(Array.prototype.slice.call(call2_args,0));a.push([e,call2])}}for(var d={},e=["get_group"].concat(Array.prototype.slice.call(arguments,0)),c=0;c<j.length;c++)b(j[c]);return d};b._i.push([e,f,c])};b.__SV=1.2;e=f.createElement("script");e.type="text/javascript";e.async=!0;e.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?
    MIXPANEL_CUSTOM_LIB_URL:"file:"===f.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";g=f.getElementsByTagName("script")[0];g.parentNode.insertBefore(e,g)}})(document,window.mixpanel||[]);

    // Enabling the debug mode flag is useful during implementation,
    // but it's recommended you remove it for production
    mixpanel.init('c3e619cb3a3d76d2e66590d3e5ae9664', {debug: true}); 
</script>

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
    // Set time zone into cookies
    document.cookie = "time_zone = " + moment.tz.guess(); 

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
         // otp button enable disable
        $(document).on('keyup','.otp-length',function(e){
        
            if($(e.target).prop('value').length>=6){
                $('.otp-btn-active').removeAttr('disabled');
            if(e.keyCode!=32){ 
                $('.otp-btn-active').removeAttr('disabled');
                } 
            }
            if($(e.target).prop('value').length < 6){
                $('.otp-btn-active').attr('disabled', 'disabled');
            }
        }) 
    });

    /* Send event in mix panel */
    function addMixpanelEvent (event) {
        mixpanel.track(event, {
            'source': "Forevory",
            'Opted out of email': true,
        });
    }
    
    var imageUrl = "{{ url('assets/images') }}";



    getNotificationUnreadCount();

    // Read unread header notification
    function getNotificationUnreadCount(){
        $.ajax({
            type: "GET",
            url:  "{{ url('/load-notifications') }}",
            data: "limit=3&page=1",
            success: function (data) {
                if(data.success){
                    if(data.total > 0){
                        $('#topMenu .notificationsMenu #notificationsMenu').addClass('received');
                    }else{
                        $('#topMenu .notificationsMenu #notificationsMenu').removeClass('received');
                    }
                }
            },
        });
    }

    /* Load notification */
    var page_num_load = 1;
    function loadNotifications() {
        $('#loadMoreNotification').addClass('d-none');
        $('#loadNotificationWindow').html('<div class="notificationLoader"><span class="btnLoader spinner-border"></span></div>');
        
        page_num_load = 1;
        $.ajax({
            type: "GET",
            url:  "{{ url('/load-notifications') }}",
            data: "limit=3&page=" + page_num_load,
            beforeSend: function() {
                $('#loadNotificationWindow').html('<div class="notificationLoader"><span class="btnLoader spinner-border"></span></div>');
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
            url:  "{{ url('/load-notifications') }}",
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
