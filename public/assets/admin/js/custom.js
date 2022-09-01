    $(document).on('change', '.checkAll', function(){
        if($(this).prop("checked")) {
            //check all 
            $(".checkBox").prop("checked", true);
        } else {
            //uncheck all
            $(".checkBox").prop("checked", false);
        }                
    });


    $(document).on('change', '.checkBox', function(){

        if($('.checkBox:checked').length == $('.checkBox').length){
            //if the length is same then untick 
            $(".checkAll").prop("checked", true);
        }else {
            //vise versa
            $(".checkAll").prop("checked", false);            
        }
    });

    // Function to update status active to inactive.
    function updateStatus(obj,message,url,status) {
        
        bootbox.confirm({
        message: message,
            centerVertical:true,
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn btn-primary ripple-effect'
                },
                cancel: {
                    label: 'No',
                    className: 'btn btn-outline-primary ripple-effect'
                }
            },
            callback: function (result) {
                obj.prop( "disabled", true );

                if(result){
                updateAccoutStatus(url,status) 
                }else{

                    if (obj.prop("checked") == true) {
                        obj.prop("checked", false)
                    } else {
                        obj.prop("checked", true)
                    }  
                    obj.prop( "disabled", false );
                }
            }
        });
             
    }   

    // Common function to update status.
    function updateAccoutStatus(url, status){
        $.ajax({
                type: "GET",
                url: url,
                data: {status: status},
                success: function (data) {
                    if (data.success) {
                    _toast.success(data.message)
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                        
                    } else {
                     _toast.error(data.message)
                    }
                }, error: function (err) {
                    _toast.error(err.message)
            }
        })
    }    
    
