[not-ajax]
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function cache_refresh()
{
	$.ajax({
		type: 'get',
		url: '{admin-page}&section=cache',
		beforeSend: function()
		{
			apadana.loading(1);
		},
		success: function(data)
		{
			$('#cache-ajax').slideUp('slow', function(){
				$(this).html(data).slideDown('slow');
			});
		},
		error: function()
		{
			alert('در ارتباط خطايي رخ داده است!');
		},
		complete: function()
		{
			apadana.loading(0);
		}
	})
}
function cache_delete(name)
{
    if(confirm('آیا از حذف فایل کش اطمینان دارید؟'))
	$.ajax({
		type: 'get',
		url: '{admin-page}&section=cache&do=delete&name='+name,
		beforeSend: function()
		{
			apadana.loading(1);
		},
		success: function(data)
		{
			$('#cache-ajax').slideUp('slow', function(){
				$(this).html(data).slideDown('slow');
			});
		},
		error: function()
		{
			alert('در ارتباط خطايي رخ داده است!');
		},
		complete: function()
		{
			apadana.loading(0);
		}
	})
}
function cache_delete_all()
{
    if(confirm('آیا از حذف فایل های کش اطمینان دارید؟'))
	$.ajax({
		type: 'get',
		url: '{admin-page}&section=cache&do=delete',
		beforeSend: function()
		{
			apadana.loading(1);
		},
		success: function(data)
		{
			$('#cache-ajax').slideUp('slow', function(){
				$(this).html(data).slideDown('slow');
			});
		},
		error: function()
		{
			alert('در ارتباط خطايي رخ داده است!');
		},
		complete: function()
		{
			apadana.loading(0);
		}
	})
}
/*]]>*/
</script>
<div id="cache-ajax">
[/not-ajax]
[cache]
<table class="apadana-table" cellpadding="0" cellspacing="0" style="margin-bottom:5px">
  <tr>
    <th>نام فایل کش</th>
    <th width="220">زمان ساخت</th>
    <th width="10">حذف</th>
  </tr>[for cache]
  <tr class="{odd-even}">
    <td dir="ltr" align=right>{name}</td>
    <td>{time}</td>
    <td><a href="javascript:cache_delete('{name}')"><img src="{site-url}engine/images/icons/cross-script.png" onmouseover="tooltip.show('حذف کش')" onmouseout="tooltip.hide()"></a></td>
 </tr>
[/for cache]
</table>
<center>
<input type="button" value="تازه سازی لیست فایل ها" onclick="cache_refresh()" />
&nbsp;&nbsp;&nbsp;
<input type="button" value="حذف فایل های کش" onclick="cache_delete_all()" />
</center>
[/cache]
[not-cache]
{function name="message" args="هیچ فایل کشی وجود ندارد!|info"}
<br><center><input type="button" value="تازه سازی لیست فایل ها" onclick="cache_refresh()" /></center>
[/not-cache]
[not-ajax]</div>[/not-ajax]