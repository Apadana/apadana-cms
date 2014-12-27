[not-ajax]<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function shoutbox_list(url)
{
	$.ajax({
		type: 'get',
		url: url,
		beforeSend: function() {
			apadana.loading(1);
		},
		success: function(data) {
			apadana.loading(0);
			$('div#shoutbox-ajax').slideUp('slow', function(){
				$(this).html(data).slideDown('slow');
			});
		},
		error: function() {
			apadana.loading(0);
			alert('در ارتباط خطايي رخ داده است!');
		}
	})
}
/*]]>*/
</script>
<div id="shoutbox-ajax" class="module-shoutbox">[/not-ajax]
[shoutbox]
<form id="shoutbox-form-options">
چینش&nbsp;&raquo;&nbsp;
<select name="order" size="1">
<option value="DESC"[desc] selected="selected" style="background: #CC0000;color: White"[/desc]>نزولی</option>
<option value="ASC"[asc] selected="selected" style="background: #CC0000;color: White"[/asc]>صعودی</option>
</select>
&nbsp;&nbsp;تعداد پیام ها در صفحه&nbsp;&raquo;&nbsp;
<input name="total" type="text" style="width:20px;text-align:center" value="{total}" maxlength="3" />
[pages]
&nbsp;&nbsp;صفحه&nbsp;&raquo;&nbsp;
<select name="page" size="1">
[for pages]<option value="{number}"[selected] selected="selected" style="background: #CC0000;color: White"[/selected]>{number}</option>[/for pages]
</select>
[/pages]
&nbsp;&nbsp;
<input type="button" value="نمایش" onclick="shoutbox_list('{site-url}?a=shoutbox&'+apadana.serialize('shoutbox-form-options'))" />
</form>
<table class="apadana-table" cellpadding="0" cellspacing="0">
  <tr>
    <th align="right">متن پیام</th>
    <th width="110">زمان ارسال</th>
    <th width="3">کاربر</th>
    <th width="5">حذف</th>
  </tr>
[for message]  <tr class="{odd-even}">
    <td align="right">{message}</td>
    <td>{time}</td>
    <td><a href="{a href='account/profile/{member}'}" title="مشاهده پروفایل {member}">{member}</a></td>
    <td>[delete]<a href="#delete" onclick="if(confirm('آیا از حذف این پیام اطمینان دارید؟')) apadana.ajax({method:'POST',action:'{site-url}?a=shoutbox&b=delete&total={total}&order={order}&page={page}&archive=true',data:'id={id}',success:function(a){apadana.html('shoutbox-ajax', a)}});return false"><img src="{site-url}engine/images/icons/cross-script.png" title="حذف پیام شماره {id}" /></a>[/delete][not-delete]<img src="{site-url}engine/images/icons/cross-script-x.png" title="شما اجازه حذف این پیام را ندارید" />[/not-delete]</td>
  </tr>
[/for message]
</table>
[/shoutbox]
[not-shoutbox]
{function name="message" args="هیچ پیامی ارسال نشده است!|info"}
[/not-shoutbox]
[not-ajax]</div>[/not-ajax]