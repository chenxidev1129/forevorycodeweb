<label>@lang('message.security_label')</label>
<select class="selectpicker form-control" id="showSecurityOption"  title="@lang('message.security_placeholder')">
    @if(!empty($request->role) && $request->role == 'administrator')
    <option selected>Full Access</option>
   @else
    <option selected>Accounts Only</option>
    @endif
</select>
<script>
    $("#showSecurityOption").selectpicker();
</script>