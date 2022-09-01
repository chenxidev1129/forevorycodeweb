
<form action="@if(!empty($getAccount->id)){{ route('access.update', $getAccount->id) }}@else{{ route('access.store') }}@endif" id="AddAccountForm" method="@if(!empty($getAccount->id)){{'patch'}}@else{{'post'}}@endif"> 
   @csrf
    <input type="hidden" name="id" value="@if(!empty($getAccount->id)) {{ $getAccount->id  }} @endif" >
    <div class="form-group">
        <label>@lang('message.name_label')</label>
        <input type="text" name="first_name" value="@if(!empty($getAccount->first_name)) {{ $getAccount->first_name }} @endif" class="form-control" placeholder="@lang('message.name_placeholder')">
    </div>
    <div class="form-group">
        <label>@lang('message.role_label')</label>
        <select class="selectpicker form-control" name="user_type" id="seclectRole" title="@lang('message.role_placeholder')">
            <option value="administrator" @if(!empty($getAccount->user_type) && $getAccount->user_type == 'administrator') {{ 'selected' }} @endif >Administrator</option>
            <option value="support"  @if(!empty($getAccount->user_type) && $getAccount->user_type == 'support') {{ 'selected' }} @endif>Support</option>
        </select>
    </div>
    <div class="form-group">
        <label>@lang('message.email_label')</label>
        <input type="email" value="@if(!empty($getAccount->email)) {{ $getAccount->email }} @endif"  name="email"  class="form-control" placeholder="@lang('message.email_placeholder')" >
    </div>
    <div class="form-group" id="showSecurity">
        @if(!empty($getAccount->user_type))
        <label>@lang('message.security_label')</label>
        <select class="selectpicker form-control" id="showSecurityOption" title="@lang('message.security_placeholder')">
            @if(!empty($getAccount->user_type) && $getAccount->user_type == 'administrator')
            <option selected>Full Access</option>
            @elseif(!empty($getAccount->user_type) && $getAccount->user_type == 'support')
            <option selected>Accounts Only</option>
            @endif
        </select>
        @endif
    </div>
    <div class="text-right mt-5 submitBtn">
        <a href="javascript:void(0);" data-dismiss="modal"  class="btn btn-outline-primary ripple-effect mr-2">@lang('message.cancel_title')</a>
        <button type="button"  onclick="saveAccoutAccess()" id="AddAccountSubmit" class="btn btn-primary ripple-effect">@lang('message.save_title')</button>
    </div>

</form>
{!! JsValidator::formRequest('App\Http\Requests\AccessAccountRequest','#AddAccountForm') !!}
<script>
$(document).ready(function() {  
  
    //Refresh selectpicker.
    $(".selectpicker").selectpicker();

    //Get security levels. 
    $('#seclectRole').on('change', function(){
        var role = this.value;
        $.ajax({
            type: "GET",
            data: {role: role},
            url:  "{{route('admin/get-security')}}",
            success: function (data) {
                if(data.success){
                    $('#showSecurity').html(data.html);
                }else{
                    _toast.error(data.message);
                }
            },
        });
    });
});

</script>
