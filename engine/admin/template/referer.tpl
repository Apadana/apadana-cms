[not-ajax]
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function referer_list(url)
{
	$.ajax({
		type: 'get',
		url: url,
		beforeSend: function() {
			apadana.loading(1);
		},
		success: function(data) {
			apadana.loading(0);
			$('div#referer-ajax').slideUp('slow', function(){
				$(this).html(data).slideDown('slow');
			});
		},
		error: function() {
			apadana.loading(0);
			alert('در ارتباط خطايي رخ داده است!');
		}
	})
}
function referer_info(ID)
{
    apadana.html('referer-infoBox-id', ID);
    apadana.html('referer-infoBox-ip', apadana.value('data-referer-ip-'+ID));
    apadana.html('referer-infoBox-time', apadana.value('data-referer-time-'+ID));
    apadana.html('referer-infoBox-domain', apadana.value('data-referer-domain-'+ID));
    apadana.$('referer-infoBox-domain').href = apadana.value('data-referer-domain-redirect-'+ID);
    apadana.html('referer-infoBox-url', apadana.value('data-referer-url-'+ID));
    apadana.$('referer-infoBox-url').href = apadana.value('data-referer-redirect-'+ID);
	apadana.infoBox('#referer-info-box', 1);
}
function referer_delete()
{
    if (confirm('آیا از حذف کل لینک دهندگان اطمینان دارید؟'))
	$.ajax({
		type: 'get',
		url: '{admin-page}&section=referer&do=delete',
		beforeSend: function() {
			apadana.loading(1);
		},
		success: function(data) {
			apadana.loading(0);
			$('div#referer-ajax').slideUp('slow', function(){
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

<div id="referer-info-box" style="display:none">
<ul>
  <li>آی دی ارجاع: <strong id="referer-infoBox-id"></strong></li>
  <li>آی پی کاربر: <strong dir="ltr" id="referer-infoBox-ip"></strong></li>
  <li>تاریخ ارجاع: <strong id="referer-infoBox-time"></strong></li>
  <li>دامین ارجاع دهنده: <strong dir="ltr"><a href="" target="_blank" data-tooltip="مشاهده دامنه"  id="referer-infoBox-domain"></a></strong></li>
  <li>آدرس کامل: <strong dir="ltr"><a href="" target="_blank" data-tooltip="مشاهده صفحه"  id="referer-infoBox-url"></a></strong></li>
</ul>
</div>

<div id="referer-ajax">
[/not-ajax]
[http-referer]{function name="message" args="ذخیره سازی لینک دهندگان در سایت شما غیرفعال است، می توانید از تنظیمات عمومی آن را فعال کنید.|info"}[/http-referer]
[referers]
<form id="form-options-show" class="fast-panel">
چینش&nbsp;&raquo;&nbsp;
<select name="order" size="1">
<option value="DESC"[desc] selected="selected"[/desc]>نزولی</option>
<option value="ASC"[asc] selected="selected"[/asc]>صعودی</option>
</select>
&nbsp;&nbsp;تعداد لینک ها در صفحه&nbsp;&raquo;&nbsp;
<input name="total" type="text" style="width:25px;text-align:center" value="{total}" maxlength="3" />
[pages]
&nbsp;&nbsp;صفحات&nbsp;&raquo;&nbsp;
<select name="page" size="1">
[for pages]<option value="{number}"[selected] selected="selected"[/selected]>{number}</option>[/for pages]
</select>
[/pages]
&nbsp;&nbsp;
<input type="button" value="نمایش" onclick="referer_list('{admin-page}&section=referer&'+apadana.serialize('form-options-show'))" />
&nbsp;&nbsp;
<input type="button" value="حذف لینک دهندگان" onclick="referer_delete()" />
</form>
<table class="apadana-table" cellpadding="0" cellspacing="0">
  <tr>
    <th width="5">#</th>
    <th width="100">آی پی کاربر</th>
    <th>دامین ارجاع دهنده</th>
    <th width="300">زمان ارجاع</th>
    <th width="30">عملیات</th>
  </tr>
[for referers]
  <tr class="{odd-even}" id="banned-{id}">
    <td>{id}
	<div style="display:none">
	<input id="data-referer-ip-{id}" type="hidden" value="{ip}" />
	<input id="data-referer-domain-{id}" type="hidden" value="{domain}" />
	<input id="data-referer-domain-redirect-{id}" type="hidden" value="{domain-redirect}" />
	<input id="data-referer-time-{id}" type="hidden" value="{time}" />
	<textarea id="data-referer-url-{id}">{url}</textarea>
	<textarea id="data-referer-redirect-{id}">{redirect}</textarea>
	</div>
	</td>
    <td dir="ltr">{ip}</td>
    <td dir="ltr">{domain}</td>
    <td><span data-tooltip="{time}" >{past-time}</span></td>
    <td><a href="javascript:referer_info({id})"><img src="{site-url}engine/images/icons/external.png" width="16" height="16" data-tooltip="اطلاعات لینک" ></a></td>
  </tr>
[/for referers]
</table>
[/referers]
[not-referers]{function name="message" args="هیچ لینک دهنده ای در سیستم ثبت نشده است!|info"}[/not-referers]
[not-ajax]</div>[/not-ajax]
