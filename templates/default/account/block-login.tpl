<div id="apadana-block-login">
[member]<center><img src="{avatar}" style="max-width:99%;margin:5px 0px" /></center>
<ul id="apadana-block-login-links">
	<li id="apadana-block-login-account"><a href="{a href='account'}">{name} عزیز خوش آمدید!</a></li>[private-messages]
	<li id="apadana-block-login-private-messages"><a href="{a href='private-messages'}">شما <font color="red">{private-messages}</font> پیام جدید دارید!</a></li>[/private-messages]
	<li id="apadana-block-login-profile-edit"><a href="{a href='account/profile-edit'}">ویرایش پروفایل</a></li>
	<li id="apadana-block-login-change-password"><a href="{a href='account/change-password'}">تغییر پسورد</a></li>
	<li id="apadana-block-login-change-avatar"><a href="{a href='account/change-avatar'}">تغییر آوارتار</a></li>
	<li id="apadana-block-login-logout"><a href="{a href='account/logout'}">خروج از حساب کاربری</a></li>
</ul>
[/member][guest]<form action="{a href='account/login'}" method="post" onsubmit="this.submit.disabled='true'">
<table cellpadding="2" cellspacing="0">
  <tr>
    <td style="width:60px">نام کاربری</td>
    <td align="left"><input name="login[username]" type="text" style="width:90%" value="" dir="ltr" /></td>
  </tr>
  <tr>
    <td>پسورد</td>
    <td align="left"><input name="login[password]" type="password" style="width:90%" value="" dir="ltr" /></td>
  </tr>
  <tr>
    <td colspan="2" align="left"><input type="submit" name="login[submit]" value="ورود" />&nbsp;<input type="button" value="عضویت" onclick="apadana.location('{a href='account/register'}')" /></td>
  </tr>
</table>
</form>[/guest]
</div>