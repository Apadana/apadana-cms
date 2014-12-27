<form name="apadana-account-register" action="{a href='account/register'}" method="post">
<table id="apadana-account-register" cellpadding="6" cellspacing="0">
  <tr>
    <td>نام کاربری</td>
    <td><input name="register[name]" type="text" size="30" value="{name}" dir="ltr" /></td>
  </tr>
  <tr>
    <td>ایمیل</td>
    <td><input name="register[email]" type="text" size="30" value="{email}" dir="ltr" /></td>
  </tr>
  <tr>
    <td>پسورد</td>
    <td><input name="register[password]" type="password" size="30" value="" dir="ltr" /></td>
  </tr>
  <tr>
    <td>تکرار پسورد</td>
    <td><input name="register[password-repeat]" type="password" size="30" value="" dir="ltr" /></td>
  </tr>
  <tr>
    <td>کد امنیتی</td>
    <td><input name="register[captcha]" type="text" size="4" value="" dir="ltr" />&nbsp;{captcha}</td>
  </tr>
  <tr>
    <td></td>
    <td><input type="submit" name="register[submit]" value="عضویت در سایت" /></td>
  </tr>
</table>
</form>