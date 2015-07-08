<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function saveOptions()
{
	$.ajax({
		type: 'post',
		url: '{admin-page}&module=account&do=options',
		data: $('#form-account-options').serialize(),
		beforeSend: function()
		{
			$('#div-ajax:visible').slideUp('slow');
			apadana.loading(1);
		},
		success: function(result)
		{
			$('#div-ajax').slideUp('slow', function(){
				$(this).html(result).slideDown('slow');
			});
		},
		error: function()
		{
			alert('در ارتباط خطايي رخ داده است!');
		},
		complete: function()
		{
			apadana.loading(0);
		}
	})
}
/*]]>*/
</script>

<div id="div-ajax"></div>
<form id="form-account-options">
<table cellpadding="6" cellspacing="0">
  <tr>
	<td width="100">عضو گیری</td>
	<td><label><input type="checkbox" name="options[register]" value="1"[register-checked] checked="checked"[/register-checked]  />&nbsp;امکان ثبت عضو جدید در سایت فعال باشد.</label></td>
  </tr>
  <tr>
	<td>فهرست کاربران</td>
	<td><label><input type="checkbox" name="options[members]" value="1"[members-checked] checked="checked"[/members-checked]  />&nbsp;نمایش فهرست کاربران فعال باشد.</label></td>
  </tr>
  <tr>
	<td>تعداد</td>
	<td>تعداد کاربران در هر صفحه: <input name="options[members-total]" value="{members-total}" type="text" style="width:25px;text-align:center" /></td>
  </tr>
  <tr>
	<td>آپلود آوارتار</td>
	<td><label><input type="checkbox" name="options[avatar]" value="1"[avatar-checked] checked="checked"[/avatar-checked]  />&nbsp;امکان آپلود آوارتار در سایت فعال باشد.</label></td>
  </tr>
  <tr>
	<td>حجم آوارتار</td>
	<td><input name="options[avatarsize]" value="{avatarsize}" type="text" style="width:25px;text-align:center" /> کیلوبایت</td>
  </tr>
  <tr>
	<td>اندازه آوارتار</td>
	<td>طول:<input name="options[maxavatardims-1]" value="{maxavatardims-1}" type="text" style="width:25px;text-align:center" />&nbsp;&nbsp;عرض:<input name="options[maxavatardims-2]" value="{maxavatardims-2}" type="text" style="width:25px;text-align:center" /></td>
  </tr>
  <tr>
	<td>نام کاربری</td>
	<td>حداقل طول نام کاربری: <input name="options[minUsername]" value="{minUsername}" type="text" style="width:25px;text-align:center" /></td>
  </tr>
  <tr>
	<td>پسورد</td>
	<td>حداقل طول پسورد: <input name="options[minPassword]" value="{minPassword}" type="text" style="width:25px;text-align:center" /></td>
  </tr>
  <tr>
	<td>ایمیل</td>
	<td><label><input type="checkbox" name="options[email]" value="1"[email-checked] checked="checked"[/email-checked]  />&nbsp;از هر ایمیل فقط یک بار بتوان استفاده کرد.</label></td>
  </tr>
  <tr>
	<td colspan="2"><input type="submit" value="ذخیره تنظیمات" onclick="saveOptions();return false" /></td>
  </tr>
</table>
</form>