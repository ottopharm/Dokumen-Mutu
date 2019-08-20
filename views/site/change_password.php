<h5 class="page-header">Change Password Form</h5>
<form id="change-password-form" method="post" novalidate>
	<div class="myform form-inline">
		<div class="container-fluid">
			<div class="row">
				<input class="easyui-passwordbox" name="old_password" id="old_password" label="Old Password" labelPosition="left" style="width:30%" required="true">
			</div>
			<div class="row">
				<input class="easyui-passwordbox" name="new_password" id="new_password" label="New Password" labelPosition="left" style="width:30%" required="true">
			</div>
			<div class="row">
				<input class="easyui-passwordbox" name="confirm_password" id="confirm_password" label="Confirm New Password" labelPosition="left" style="width:30%" required="true">
			</div>
			<div class="row">
				<label class="textbox-label textbox-label-left">&nbsp;</label>
				<button type="button" class="btn btn-primary btn-sm" onClick="submitChangePassword()">Save</button>
			</div>
		</div>
	</div>
</form>
<script type="text/javascript">
function submitChangePassword() {
	var oldpass = $('#old_password').textbox('getValue');
	var newpass = $('#new_password').textbox('getValue');
	var confirmpass = $('#confirm_password').textbox('getValue');
	if(oldpass == '' || newpass == '' || confirmpass == '') $.messager.alert('Warning','Please fill your passwords','warning');
	else {
		if($('#confirm_password').textbox('getValue') != $('#new_password').textbox('getValue'))
			$.messager.alert('Warning','Confirm password did not match','warning');
		else {
			$.messager.progress({
				title : 'Please wait',
				msg : 'Processing...'
			});
			$('#change-password-form').form('submit',{
		        url: '<?php echo $this->createUrl('/site/saveNewPassword'); ?>',
		        onSubmit: function(){
		            return $(this).form('validate');
		        },
		        success: function(result){
		        	$.messager.progress('close');
		            var result = eval('('+result+')');
		            if(result.success) {
						var currTab = $('#main-tabs').tabs('getSelected');
						var tabIdx = $('#main-tabs').tabs('getTabIndex',currTab);
						$('#main-tabs').tabs('close',tabIdx);
						jQuery.messager.show({
							title: 'Success',
							msg: result.msg,
							timeout:5000,
						});
					} else {
						jQuery.messager.show({
							title : 'Error',
							msg : result.msg,
							timeout:10000,
						});
					}
		        }
		    });
		}
	}
}
</script>