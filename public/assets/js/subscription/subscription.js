 /* load get subsciption form */
$(document).ready(function(){
    getSubcriptions();
});

/* Load subscription options */
function getSubcriptions() {
    
    $.ajax({
        url: loadSubscriptionWindow,
        type: "GET", 
        data: [],
        dataType: 'JSON',
        success: function (response)
        {

            if (response.success) {
                $('aside.startFreeTrial').html(response.data);
                  /* Scroll edit profile side bar to top */ 
                $("#rightSidebarSubscriptioWindoe").animate({ scrollTop: 0 }, "slow");
                $('aside.startFreeTrial').addClass('open');
                $('body').addClass('overflow-hidden');
                $('body').append('<div class="rightSidebar-overlay"></div>');
            } else {
                _toast.error(response.message) 
            }
        
        }
    });
}   