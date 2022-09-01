
@extends('user.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ url('assets/css/family-tree-style.css') }}" type="text/css">
@endsection

@section('content')
@section('title', __('message.family_tree'))
<!-- Main -->
<main class="main-content familyTreePage">
	<!-- profile -->
	<section class="profile py-4 h-100">
		<div class="profile_top h-100">
				<!-- <h1 class="h34 font-nbd title">@lang('message.family_tree')</h1> -->
				<!-- <input type="button" id="sendData" value="send data" onclick="$.send_Family({url: 'save_family.php'})"/> -->
				<div class="treeWrap">
                 <div id="pk-family-tree"></div>            
                </div>
		</div>
	</section>
</main>
@endsection

@section('js')
<script src="{{ url('assets/js/jquery-ui.min.js') }}"></script>
<script src="{{ url('assets/js/guest-family-tree.js?').time() }}"></script>
<script>
	var profileId = "{{$profile->id}}";
	loadFamilyTree();
	function loadFamilyTree(){
        if(profileId){
            $.ajax({
                url: "{{ route('get-family-tree')}}",
                type: 'GET', 
                data: {profileId : profileId },
                dataType: 'JSON',
                success: function (response)
                {
                    if (response.success) {
						$('#pk-family-tree').pk_family_create({
							data: response.data
						});
                    } else {
                        _toast.error(response.message) 
                    }
                    
                }, error: function (err) {
                    var errors = jQuery.parseJSON(err.responseText);
                    _toast.error(errors.message)
                },
            });
        }
    }
	/* initialize new familty tree */
    // $('#pk-family-tree').pk_family();
    
</script>
@endsection