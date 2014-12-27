[message]{message}
[/message][form-1]<form name="apadana-account-forget" action="{a href='account/forget'}" method="post">
<table id="apadana-account-forget" cellpadding="6" cellspacing="0">
  <tr>
    <td width="120">نام کاربری</td>
    <td><input name="forget[name]" type="text" size="30" dir="ltr" /></td>
  </tr>
  <tr>
    <td>ایمیل حساب کاربری</td>
    <td><input name="forget[email]" type="text" size="30" dir="ltr" /></td>
  </tr>
  <tr>
    <td>کد امنیتی</td>
    <td><input name="forget[captcha]" type="text" size="4" value="" dir="ltr" />&nbsp;{captcha}</td>
  </tr>
  <tr>
    <td></td>
    <td><input type="submit" name="forget[submit]" value="شروع عملیات بازیابی پسورد" /></td>
  </tr>
</table>
</form>[/form-1]
[form-2]{function name="message" args="لطفا پسورد مورد نظر خود را وارد کنید.|info"}
<form name="apadana-account-forget" action="{a href='account/forget/{name}/{forget}'}" method="post">
<table id="apadana-account-forget" cellpadding="6" cellspacing="0">
  <tr>
    <td>پسورد</td>
    <td><input name="forget[pass1]" type="Password" size="30" dir="ltr" /></td>
  </tr>
  <tr>
    <td>تکرار پسورد</td>
    <td><input name="forget[pass2]" type="Password" size="30" dir="ltr" /></td>
  </tr>
  <tr>
    <td></td>
    <td><input type="submit" name="forget[submit]" value="تغییر پسورد" /></td>
  </tr>
</table>
</form>[/form-2]