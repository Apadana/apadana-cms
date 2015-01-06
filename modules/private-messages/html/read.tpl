<table id="apadana-private-messages-read" cellpadding="5" cellspacing="0">
  <tr>
    <td width="80">عنوان</td>
    <td>{subject}</td>
  </tr>
  <tr>
    <td>متن</td>
    <td>{text}</td>
  </tr>
  <tr>
    <td>ارسال کننده</td>
    <td>{sender}[reply]&nbsp;<a href="javascript:void(0)" onClick="if(confirm(&quot;آیا از ارسال پاسخ برای این پیام اطمینان دارید؟&quot;))document.location=&quot;{a href='private-messages/new/{id}'}&quot;"><img src="{site-url}engine/images/icons/pencil.png" width="16" height="16" title="ارسال پاسخ" /></a>[/reply]</td>
  </tr>
  <tr>
    <td>گیرنده</td>
    <td>{receiver}</td>
  </tr>
  <tr>
    <td>زمان ارسال</td>
    <td><span title="{date}">{past-time}</span></td>
  </tr>
  <tr>
    <td>حذف</td>
    <td><a href="javascript:void(0)" onClick="if(confirm(&quot;آیا از حذف پیام اطمینان دارید؟&quot;))document.location=&quot;{a href='private-messages/remove/{id}'}&quot;"><img src="{site-url}engine/images/icons/cross-script.png" width="16" height="16" title="حذف پیام" /></a></td>
  </tr>
</table>