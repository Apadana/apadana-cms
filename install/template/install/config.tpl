<script>set_percent(250)</script>
<form id="form-config" onSubmit="return false">
<div class="info">
	<h1>پیکربندی دیتابیس</h1>
	لطفا اطلاعات لازم برای اتصال به دیتابیس را معین کنید.
</div>
<table cellpadding="0" cellspacing="0" class="padding">
  <tr>
    <td style="width:230px; cursor: help" title="معمولا localhost می باشد">نام هاست <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(Host Name)</font></td>
    <td><input name="config[host]" id="input-host" type="text" value="localhost" style="width:350px; float: left; direction: ltr" /></td>
  </tr>
  <tr>
    <td>نام کاربری <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(MySQL User Name)</font></td>
    <td><input name="config[user]" id="input-user" type="text" style="width:350px; float: left; direction: ltr" value="" /></td>
  </tr>
  <tr>
    <td>پسورد دیتابیس <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(MySQL Password)</font></td>
    <td><input name="config[password]" id="input-password" type="text" style="width:350px; float: left; direction: ltr" value="" /></td>
  </tr>
  <tr>
    <td>نام دیتابیس <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(MySQL Database Name)</font></td>
    <td><input name="config[name]" id="input-name" type="text" style="width:350px; float: left; direction: ltr" value="" /></td>
  </tr>
  <tr>
    <td>پیشوند تیبل های دیتابیس <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(Database Prefix)</font></td>
    <td><input name="config[prefix]" id="input-prefix" type="text" style="width:350px; float: left; direction: ltr" value="{prefix}" /></td>
  </tr>
</table>

<div class="info">
	<h1>پیکربندی آدرس سایت</h1>
	لطفا اطلاعات زیر را برسی کنید، و در صورت صحیح نبود آن ها را اصلاح کنید.
</div>
<table cellpadding="0" cellspacing="0" class="padding">
  <tr>
    <td style="width:230px">دامنه سایت <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(Domain)</font></td>
    <td><input name="config[domain]" id="input-domain" type="text" value="{domain}" style="width:350px; float: left; direction: ltr" /></td>
  </tr>
  <tr>
    <td>محل نصب سایت <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(Path)</font></td>
    <td><input name="config[path]" id="input-path" type="text" style="width:350px; float: left; direction: ltr" value="{path}" /></td>
  </tr>
  <tr>
    <td>آدرس کامل سایت <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(Full Url)</font></td>
    <td><input name="config[url]" id="input-url" type="text" style="width:350px; float: left; direction: ltr" value="{url}" /></td>
  </tr>
  <tr>
    <td></td>
    <td><button onClick="install_config()" disabled="disabled" id="button-disabled">ساختن فایل پیکربندی</button><button onClick="install_check('config')" style="margin-left:10px">برسی اطلاعات</button></td>
  </tr>
</table>

</form>
[ajax]<div class="clear"></div>[/ajax]