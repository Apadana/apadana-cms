[success]
<script>set_percent(350)</script>

<div class="info">
<h1>پایان پیکربندی</h1>
فایل پیکربندی با موفقیت ایجاد شده است، در مرحله ی بعدی اطلاعات لازم در دیتابیس سایت نوشته خواهد شد.<br>اطلاعات ثبت شده به شرح زیر است.
</div>


<table cellpadding="0" cellspacing="0" class="padding">
<tr>
  <td style="width:290px">نام هاست <font color="#CCCCCC" size="1" dir="ltr">(Host Name)</font></td>
  <td><b>{database_host}</b></td>
</tr>
<tr>
  <td>نام کاربری <font color="#CCCCCC" size="1" dir="ltr">(MySQL User Name)</font></td>
  <td><b>{database_user}</b></td>
</tr>
<tr>
  <td>پسورد دیتابیس <font color="#CCCCCC" size="1" dir="ltr">(MySQL Password)</font></td>
  <td><b>{database_password}</b></td>
</tr>
<tr>
  <td>نام دیتابیس <font color="#CCCCCC" size="1" dir="ltr">(MySQL Database Name)</font></td>
  <td><b>{database_name}</b></td>
</tr>
<tr>
  <td>دامنه سایت <font color="#CCCCCC" size="1" dir="ltr">(Domain)</font></td>
  <td><b>{domain}</b></td>
</tr>
<tr>
  <td>محل نصب سایت <font color="#CCCCCC" size="1" dir="ltr">(Path)</font></td>
  <td dir=ltr><b>{path}</b></td>
</tr>
<tr>
  <td>آدرس سایت <font color="#CCCCCC" size="1" dir="ltr">(Full Url)</font></td>
  <td dir=ltr><b>{url}</b></td>
</tr>
</table>

<button onClick="install_load('db-insert')">نوشتن اطلاعات دیتابیس</button>
[/success]

[write]
<div class="info">
<h1>خطا در پیکربندی</h1>
در نوشتن فایل پیکربندی خطایی رخ داده مجدد تلاش کنید.
</div>
<button onClick="install_load('config')">بازگشت</button>
[/write]

[connect]
<div class="info">
<h1>خطا در پیکربندی</h1>
اطلاعات وارد شده صحیح نیست لطفا بازگردید و مجدد تلاش کنید.
</div>
<button onClick="install_load('config')">بازگشت</button>
[/connect]
[ajax]<div class="clear"></div>[/ajax]