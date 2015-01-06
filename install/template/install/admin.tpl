<script>set_percent(520)</script>
<div class="info">
	<h1>اطلاعات کاربری مدیر سایت</h1>
	لطفا اطلاعات مورد نیاز برای ساختن مدیر سایت را وارد کنید، نام کاربری باید حداقل 4 حرف باشد و پسورد باید حداقل 6 حرف باشد.
</div>
<form id="form-admin" onSubmit="return false" style="margin:0;">
<table cellpadding="0" cellspacing="0" class="padding">
  <tr>
    <td style="width:230px">نام کاربری مدیر <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(Admin User Name)</font></td>
    <td><input name="admin[name]" id="input-name" type="text" value="admin" style="width:350px; float: left; direction: ltr" /></td>
  </tr>
  <tr>
    <td>ایمیل مدیر <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(Admin Email)</font></td>
    <td><input name="admin[email]" id="input-email" type="text" value="" style="width:350px; float: left; direction: ltr" /></td>
  </tr>
  <tr>
    <td>پسورد مدیر <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(Admin Password)</font></td>
    <td><input name="admin[pass1]" id="input-pass1" type="Password" value="" style="width:350px; float: left; direction: ltr" /></td>
  </tr>
  <tr>
    <td>تکرار پسورد مدیر <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(Admin Password)</font></td>
    <td><input name="admin[pass2]" id="input-pass2" type="Password" value="" style="width:350px; float: left; direction: ltr" /></td>
  </tr>
  <tr>
    <td></td>
    <td><button onClick="install_admin()" disabled="disabled" id="button-disabled">ایجاد مدیر سایت</button><button onClick="install_check('admin')" style="margin-left:10px">برسی اطلاعات</button></td>
  </tr>
</table>
</form>
[ajax]<div class="clear"></div>[/ajax]