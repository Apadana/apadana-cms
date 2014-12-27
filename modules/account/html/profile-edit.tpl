[message]{message}
[/message]<form name="apadana-account-profile-edit" action="{a href='account/profile-edit'}" method="post">
<table id="apadana-account-profile-edit" cellpadding="6" cellspacing="0">
  <tr>
    <td width="100">نام کاربری</td>
    <td><input type="text" size="30" value="{name}" disabled="disabled" dir="ltr" /></td>
  </tr>
  <tr>
    <td>نام مستعار</td>
    <td><input name="profileEdit[alias]" type="text" size="30" value="{alias}" /></td>
  </tr>
  <tr>
    <td>ملیت</td>
    <td>{nationality}</td>
  </tr>
  <tr>
    <td>محل زندگی</td>
    <td><input name="profileEdit[location]" type="text" size="30" value="{location}" /></td>
  </tr>
  <tr>
    <td>جنسیت</td>
    <td>{gender}</td>
  </tr>
  <tr>
    <td>ایمیل</td>
    <td><input name="profileEdit[email]" type="text" size="30" value="{email}" dir="ltr" /></td>
  </tr>
  <tr>
    <td>وب سایت</td>
    <td><input name="profileEdit[web]" type="text" size="30" value="{web}" dir="ltr" /></td>
  </tr>
  <tr>
    <td>عضویت در خبرنامه اختصاصی اعضا</td>
    <td>{newsletter}</td>
  </tr>
  <tr>
    <td>امضا</td>
    <td><textarea name="profileEdit[signature]" cols="50" rows="7">{signature}</textarea></td>
  </tr>
  <tr>
    <td></td>
    <td><input type="submit" name="profileEdit[submit]" value="ویرایش پروفایل" /></td>
  </tr>
</table>
</form>