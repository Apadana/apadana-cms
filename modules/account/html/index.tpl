<table id="apadana-account-profile" cellpadding="6" cellspacing="0">
  <tr>
    <td colspan="2" align="center"><img src="{avatar}"></td>
  </tr>
  <tr>
    <td width="95">نام کاربری</td>
    <td>{name}</td>
  </tr>
  <tr>
    <td>گروه کاربر</td>
    <td>{group}&nbsp;<img src="{group-icon}" title="{group-name}" class="apadana-group-icon" /></td>
  </tr>[module=private-messages]
  <tr>
    <td>پیام های خصوصی</td>
    <td>[unread-messages]<font color=red><b>{unread-messages}</b> پیام خوانده نشد![/unread-messages][not-unread-messages]<font color=green>هیچ پیام خوانده نشده ای ندارید![/not-unread-messages]</font></td>
  </tr>[/module]
  <tr>
    <td>نام مستعار</td>
    <td>{alias}</td>
  </tr>
  <tr>
    <td>ملیت</td>
    <td><span dir="ltr">{nationality}</span></td>
  </tr>[location]
  <tr>
    <td>محل زندگی</td>
    <td>{location}</td>
  </tr>[/location]
   <tr>
    <td>جنسیت</td>
    <td>{gender}</td>
  </tr>
  <tr>
    <td>تاریخ عضویت</td>
    <td>{date}</td>
  </tr>
  <tr>
    <td>آخرین بازدید</td>
    <td>{lastvisit}</td>
  </tr>
  <tr>
    <td>بازدیدها</td>
    <td><b>{visits}</b> بار از زمان عضویت در سایت لاگین کرده اید.</td>
  </tr>[web]
  <tr>
    <td>وب سایت</td>
    <td><a href="{web}" target="_blank" rel="nofollow">وب سایت</a></td>
  </tr>[/web][signature]
  <tr>
    <td>امضا</td>
    <td>{signature}</td>
  </tr>[/signature]
  <tr>
    <td>آدرس کوتاه</td>
    <td><a href="javascript:void(0)" onClick="apadana.hideID('link-profile-{name}');apadana.showID('show-profile-{name}')" id="link-profile-{name}">مشاهده آدرس</a><span id="show-profile-{name}" style="display:none"><a href="{site-url}u@{name}" target="_blank"  dir="ltr" title="آدرس کوتاه پروفایل شما در سایت ما"><b>{site-url}u@{name}</b></a>&nbsp;&nbsp;<a href="javascript:void(0)" onClick="apadana.showID('link-profile-{name}');apadana.hideID('show-profile-{name}')"><font size="1" color="#999999">(مخفی کن!)</font></a></span></td>
  </tr>
</table>