<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function sendContactUs()
{
	$.ajax({
		type: 'post',
		url: '{url}',
		data: $('#form-contact-us').serialize(),
		beforeSend: function() {
			apadana.loading(1);
		},
		success: function(data) {
			$('div#ajax-contact-us').slideUp('slow', function(){
				$(this).html(data).slideDown('slow');
			});
		},
		error: function() {
			alert('در ارتباط خطايي رخ داده است!');
		},
		complete: function() {
			apadana.loading(0);
		}
	})
}
/*]]>*/
</script>
<div id="ajax-contact-us"></div>
<form id="form-contact-us" onsubmit="sendContactUs();return false">
<table id="table-contact-us" cellpadding="5" cellspacing="0">
  <tr>
    <td width="90">نام شما&nbsp;<font color="red">*</font></td>
    <td><input name="contact-us[name]" type="text" size="30" value="{name}" lang="fa-ir" /></td>
  </tr>
  <tr>
    <td>ایمیل شما&nbsp;<font color="red">*</font></td>
    <td><input name="contact-us[email]" type="text" size="30" value="{email}" dir="ltr" /></td>
  </tr>
  <tr>
    <td>وبسایت شما</td>
    <td><input name="contact-us[website]" type="text" size="30" value="{website}" dir="ltr" /></td>
  </tr>
  <tr>
    <td>دریافت کننده</td>
    <td>[receiver-admin]<select name="contact-us[receiver]" size="1">[for receiver]<option value="{name}">{alias}</option>[/for receiver]</select>[/receiver-admin][receiver-user]این پیام برای {receiver} ارسال خواهد شد.[/receiver-user]</td>
  </tr>
  <tr>
    <td>متن پیام&nbsp;<font color="red">*</font></td>
    <td><textarea name="contact-us[message]" rows="6" cols="40" lang="fa-ir"></textarea></td>
  </tr>
  <tr>
    <td>کد امنیتی&nbsp;<font color="red">*</font></td>
    <td><input name="contact-us[captcha]" type="text" style="width:50px;text-align:center" value="" dir="ltr" maxlength="4" />&nbsp;{captcha}</td>
  </tr>
  <tr>
    <td></td>
    <td><input type="submit" value="ارسال پیام" />&nbsp;<input type="reset" value="پاک کردن فرم" /></td>
  </tr>
</table>
</form>