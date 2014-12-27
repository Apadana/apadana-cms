[not-disabled][message]{message}
[/message]<form name="apadana-account-change-avatar" action="{a href='account/change-avatar'}" method="POST" enctype="multipart/form-data">
<table id="apadana-account-change-avatar" cellpadding="6" cellspacing="0">
  <tr>
    <td align="center"><img src="{avatar}"></td>
  </tr>
  <tr>
    <td align="center"><input name="fileAvatar" type="file" value="" size="30" dir="ltr" /></td>
  </tr>
  <tr>
    <td align="center"><a href="javascript:void(0)" onClick="if (confirm(&quot;آیا از حذف آوارتار خود اطمینان دارید؟&quot;))document.location=&quot;{a href='account/remove-avatar'}&quot;"><img src="{site-url}engine/images/icons/cross-script.png" width="16" height="16"> آوارتار من را حذف کن!</a></td>
  </tr>
  <tr>
    <td align="center"><input type="submit" name="changeAvatar[submit]" value="تغییر آوارتار" /></td>
  </tr>
</table>
</form>[/not-disabled]
[disabled]
<center><img src="{avatar}"></center>
{disabled}[/disabled]