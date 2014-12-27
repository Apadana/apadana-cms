<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fa-ir" dir="rtl">
<head profile="http://gmpg.org/xfn/11">
{head}
<link href="{site-url}engine/admin/template/styles/login.css" type="text/css" rel="stylesheet" />
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function admin_login()
{
	$.ajax({
		type: 'post',
		url: '{admin-page}&section=login',
		data: $('#login-form').serialize(),
		dataType: 'json',
		beforeSend: function()
		{
			$('#apadana-result').slideUp('slow');
			$('#apadana-loader').html('<img src="{site-url}engine/images/loading/loader-18.gif">&nbsp;لطفا صبر کنید ...').fadeIn('slow');
		},
		success: function(data)
		{
			if (data.result == 'yes')
			{
				apadana.location(data.url);
			}
			else
			{
				$('#apadana-result').slideUp('slow', function(){
					$(this).html('<div id="apadana-error">اطلاعات وارد شده صحیح نیست!</div>').slideDown('slow');
				});
			}
		},
		error: function()
		{
			$('#apadana-result').slideUp('slow', function(){
				$(this).html('<div id="apadana-error">در ارتباط با سرور خطای ناشناخته ای رخ داده، مجدد تلاش کنید!</div>').slideDown('slow');
			});
		},
		complete: function()
		{
			$('#apadana-loader').hide()
		}
	})
}
/*]]>*/
</script>
</head>
<body>
<div align="center">
<div id="apadana-wrap">
<div id="apadana-result"></div>
<form id="login-form" action="{admin-page}&section=login">
<input name="login[username]" type="text" value="User Name" onblur="if(this.value=='')this.value='User Name'" onfocus="if(this.value=='User Name')this.value=''" class="input" />
<input name="login[password]" type="password" value="password" onblur="if(this.value=='')this.value='password'" onfocus="if(this.value=='password')this.value=''" class="input" />
<input name="login[submit]"type="submit" value="ورود به سیستم" id="submit" onclick="admin_login(); return false" />
<input type="button" value="بازیابی پسورد" id="lostpassword" onclick="document.location='{a href='account/forget'}'" />
<input name="login[redirect]" type="hidden" value="{redirect}" />
</form>
<div class="apadana-clear"></div>
</div>
<!-- /apadana-wrap -->
<div id="apadana-copyright">Powered by <a href="http://www.apadanacms.ir/" target="_blank" rel="copyright">Apadana Cms</a> copyright &copy; {function name="date" args="Y"} by Iman Moodi.</div>
<div id="apadana-loader"></div>
</div>
</body>
</html>