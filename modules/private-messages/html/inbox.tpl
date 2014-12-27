[list]<table class="apadana-table" id="apadana-private-messages-inbox" cellpadding="0" cellspacing="0">
<thead>
  <tr>
    <th align="right">عنوان</th>
    <th width="100">ارسال کننده</th>
    <th width="10">خواندن</th>
    <th width="10">حذف</th>
  </tr>
</thead>
<tbody>[for list]
	<tr class="{odd-even}">
		<td align="right"><span title="ارسال شده در {past-time}">{subject}</span></td>
		<td>{sender}</td>
		<td><a href="{a href='private-messages/read/{id}'}"><img src="{site-url}engine/images/icons/magnifier.png" width="16" height="16" title="خواندن پیام" /></a></td>
		<td><a href="javascript:void(0)" onClick="if(confirm(&quot;آیا از حذف پیام اطمینان دارید؟&quot;))document.location=&quot;{a href='private-messages/remove/{id}'}&quot;"><img src="{site-url}engine/images/icons/cross-script.png" width="16" height="16" title="حذف پیام" /></a></td>
	</tr>[/for list]
</tbody>
</table>
[/list]
[not-list]{function name="message" args="هیچ پیام دریافتی وجود ندارد!|info"}[/not-list]