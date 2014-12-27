<div id="apadana-block-account">
<fieldset id="apadana-block-account-your">
<legend>اطلاعات شما</legend>
[member]<center><img src="{avatar}" style="max-width:99%;margin:5px 0px" /></center>
[/member]<img src="{site-url}engine/images/blocks/account/user.png" height="16" width="16" />&nbsp;خوش آمدید, {name}<br />
<img src="{site-url}engine/images/blocks/account/your_ip.png" height="16" width="16" />&nbsp;آی پی شما: <b>{ip}</b>[member]<br />
<img src="{site-url}engine/images/blocks/account/logout.png" height="16" width="16" />&nbsp;<a href="{a href='account/logout'}" style="color:red"><b>خروج</b></a>[/member]
</fieldset>
[member][module=private-messages]
<fieldset id="apadana-block-account-private-messages">
<legend>پیغامهای شخصی</legend>
<img src="{site-url}engine/images/blocks/account/email-r.gif" height="10" width="14">&nbsp;خوانده نشده : <b style="color:red">{newpms}</b><br />
<img src="{site-url}engine/images/blocks/account/email-g.gif" height="10" width="14">&nbsp;خوانده شده : <b>{oldpms}</b><br />
<img src="{site-url}engine/images/blocks/account/email-c.gif" height="10" width="14">&nbsp;ارسالی ها: <b>{sendpms}</b><br />
<img src="{site-url}engine/images/blocks/account/email-y.gif" height="10" width="14">&nbsp;کل پیام ها: <b>{allpms}</b>
</fieldset>
[/module][/member][guest]
<fieldset id="apadana-block-account-login">
<legend>ورود به سیستم</legend>
<form action="{a href='account/login'}" method="post" onsubmit="this.submit.disabled='true'">
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
</form>
</fieldset>
[/guest]<fieldset id="apadana-block-account-members">
<legend>کاربران سایت</legend>
<img src="{site-url}engine/images/blocks/account/member_new.png" height="16" width="16">&nbsp;آخرین: <a href="{a href='account/profile/{last-member}'}"><b>{last-member}</b></a><br />
<img src="{site-url}engine/images/blocks/account/today.png" height="16" width="16">&nbsp;امروز : <b>{members-today}</b><br />
<img src="{site-url}engine/images/blocks/account/yesterday.png" height="16" width="16">&nbsp;دیروز: <b>{members-yesterday}</b><br />
<img src="{site-url}engine/images/blocks/account/month.png" height="16" width="16">&nbsp;ماه: <b>{members-month}</b><br />
<img src="{site-url}engine/images/blocks/account/total_users.png" height="16" width="16">&nbsp;مجموع: <b>{members-total}</b>
</fieldset>
<fieldset id="apadana-block-account-visitors">
<legend>بازدیدکنندگان</legend>
<img src="{site-url}engine/images/blocks/account/user-1.png" height="16" width="16">&nbsp;مهمان: <b>{count-guest}</b><br />
<img src="{site-url}engine/images/blocks/account/online.png" height="16" width="16">&nbsp;عضو: <b>{count-member}</b><br />
<img src="{site-url}engine/images/blocks/account/user-2.png" height="16" width="16">&nbsp;مجموع: <b>{count-total}</b>
</fieldset>
<fieldset id="apadana-block-account-onlines">
<legend>کاربران آنلاین</legend>
<div style="max-height:150px;overflow-y:auto">
[for online]<div>[flag]<img src="{site-url}modules/counter/images/flags/{flag}.gif" width="15" height="9" title="{country}" />&nbsp;[/flag]<a href="{page}" title="مشاهده صفحه در حال نمایش برای این کاربر" rel="nofollow"><img src="{site-url}engine/images/blocks/account/chain.png" width="15" height="9" alt="chain" /></a>&nbsp;[user]<a href="{a href='account/profile/{member}'}" title="پروفایل {member}">{name}</a>[/user][guest]{name}[/guest]</div>
[/for online]</div>
</fieldset>
</div>