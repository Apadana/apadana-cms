[not-ajax]
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function post_list(url)
{
	$.ajax({
		type: 'get',
		url: url,
		beforeSend: function() {
			apadana.loading(1);
		},
		success: function(data) {
			$('div#ajax-content-posts').slideUp('slow', function(){
				$(this).html(data).slideDown('slow');
			});
		},
		error: function() {
			alert('در ارتباط خطايي رخ داده است!');
		},
		complete: function() {
			apadana.loading(0);
		}
	})
}
function post_title(id, action)
{
	if (action == 'save')
	{
		if (apadana.value('post-title-value-'+id) == '')
		{
			alert('عنوان را ننوشته اید!');
			return apadana.$('post-title-value-'+id).focus();
		}

		apadana.ajax({
			method: 'post',
			action: '{admin-page}&module=posts&do=posts-title',
			data: 'id='+id+'&title='+apadana.value('post-title-value-'+id),
			success: function(data)
			{
				if (apadana.trim(data) == 'ok')
				{
					apadana.html('post-title-'+id, '<a href="javascript:post_title('+id+')" id="post-title-t-'+id+'">'+apadana.value('post-title-value-'+id)+'</a>');
				}
				else
				{
					alert(data);
					return apadana.$('post-title-value-'+id).focus();
				}
			}
		})
	}
	else
	{
		f = '<input id="post-title-value-'+id+'" type="text" value="'+apadana.$('post-title-t-'+id).innerHTML+'" size="60">&nbsp;<img src="{site-url}engine/images/icons/tick-button.png" onclick="post_title('+id+', \'save\')" style="cursor:pointer">';
		apadana.$('post-title-'+id).innerHTML = f;
	}
}
function post_approve(ID)
{
	apadana.ajax({
		method: 'get',
		action: '{admin-page}&module=posts&do=posts-approve',
		data: 'id='+ID,
		success: function(approve)
		{
			approve = apadana.trim(approve);
			if (approve == 'ok')
			{
				apadana.changeSrc('post-approve-'+ID, '{site-url}engine/images/icons/plus-button.png');
				apadana.attr('post-approve-'+ID, 'onmouseover', 'tooltip.show(\'منتشر شده\')');
			}
			else if (approve == 'no')
			{
				apadana.changeSrc('post-approve-'+ID, '{site-url}engine/images/icons/minus-button.png');
				apadana.attr('post-approve-'+ID, 'onmouseover', 'tooltip.show(\'چرکنویس\')');
			}
			else
			{
				alert(approve);
			}
		}
	})
}
function post_fixed(ID)
{
	apadana.ajax({
		method: 'get',
		action: '{admin-page}&module=posts&do=posts-fixed',
		data: 'id='+ID,
		success: function(fixed)
		{
			fixed = apadana.trim(fixed);
			if (fixed == 'ok')
			{
				apadana.changeSrc('post-fixed-'+ID, '{site-url}engine/images/icons/star.png');
				apadana.attr('post-fixed-'+ID, 'onmouseover', 'tooltip.show(\'پست ثابت\')');
			}
			else if (fixed == 'no')
			{
				apadana.changeSrc('post-fixed-'+ID, '{site-url}engine/images/icons/star-empty.png');
				apadana.attr('post-fixed-'+ID, 'onmouseover', 'tooltip.show(\'پست معمولی\')');
			}
			else
			{
				alert(fixed);
			}
		}
	})
}
function post_delete(ID)
{
	if (confirm('آیا از حذف این پست اطمینان دارید؟'))
	$.ajax({
		type: 'get',
		url: '{admin-page}&module=posts&do=posts-delete',
		data: 'id='+ID,
		beforeSend: function() {
			apadana.loading(1);
		},
		success: function(data) {
			$('div#ajax-content-posts').slideUp('slow', function(){
				$(this).html(data).slideDown('slow');
			});
		},
		error: function() {
			alert('در ارتباط خطايي رخ داده است!');
		},
		complete: function() {
			apadana.loading(0);
		}
	})
}
/*]]>*/
</script>

<!-- TAB START-->
<div class="content">
<div class="content-tabs">
<ul>
  <li class="tab-on" id="tab-id-1" onclick="apadana.location('{admin-page}&module=posts')">مدیریت پست ها</li>
  <li class="tab-off" id="tab-id-2" onclick="apadana.location('{admin-page}&module=posts&do=posts-new')">پست جدید</li>
  <li class="tab-off" id="tab-id-3" onclick="apadana.location('{admin-page}&section=comments&type=posts')">نظرات پست ها</li>
  <li class="tab-off" id="tab-id-4" onclick="apadana.location('{admin-page}&module=posts&do=categories')">موضوعات پست ها</li>
</ul>
</div>
<div class="content-main">
<div class="content-space">

<div id="ajax-content-posts">
[/not-ajax]
[posts]
<form id="form-options-show" class="fast-panel">
چینش&nbsp;&raquo;&nbsp;
<select name="order" size="1">
<option value="DESC"[desc] selected="selected"[/desc]>نزولی</option>
<option value="ASC"[asc] selected="selected"[/asc]>صعودی</option>
</select>
&nbsp;&nbsp;تعداد پست ها در صفحه&nbsp;&raquo;&nbsp;
<input name="total" type="text" style="width:25px;text-align:center" value="{total}" maxlength="3" />
[pages]
&nbsp;&nbsp;صفحات&nbsp;&raquo;&nbsp;
<select name="page" size="1">
[for pages]<option value="{number}"[selected] selected="selected"[/selected]>{number}</option>[/for pages]
</select>
[/pages]
&nbsp;&nbsp;
<input type="button" value="نمایش" onclick="post_list('{admin-page}&module=posts&'+apadana.serialize('form-options-show'))" />
</form>
<table class="apadana-table" cellpadding="0" cellspacing="0">
  <tr>
	<th width="5">#</th>
	<th align="right">عنوان پست</th>
	<th width="60">پست ثابت</th>
	<th width="30">وضعیت</th>
	<th width="5">بازدیدها</th>
	<th width="20">عملیات</th>
  </tr>
  [for posts]
  <tr class="{odd-even}"[next] style="background:#FFFFCC"[/next]>
	<td>{id}</td>
	<td align="right" id="post-title-{id}"><a href="javascript:post_title({id})" id="post-title-t-{id}">{title}</a></td>
	<td><a href="javascript:post_fixed({id})"><img src="{site-url}engine/images/icons/[fixed]star[/fixed][not-fixed]star-empty[/not-fixed].png" width="16" height="16" onmouseover="tooltip.show('[fixed]پست ثابت[/fixed][not-fixed]پست معمولی[/not-fixed]')" onmouseout="tooltip.hide()" id="post-fixed-{id}" /></a></td>
	<td>[next]<img src="{site-url}engine/images/icons/light-bulb.png" width="16" height="16" onmouseover="tooltip.show('انتشار برای آینده!<br/>زمان انتشار: {data}')" onmouseout="tooltip.hide()">[/next][not-next]<a href="javascript:post_approve({id})"><img src="{site-url}engine/images/icons/[approve]plus-button[/approve][not-approve]minus-button[/not-approve].png" width="16" height="16" onmouseover="tooltip.show('[approve]منتشر شده[/approve][not-approve]چرکنویس[/not-approve]')" onmouseout="tooltip.hide()" id="post-approve-{id}" /></a>[/not-next]</td>
	<td>{hits}</td>
	<td><a href="{admin-page}&module=posts&do=posts-edit&id={id}"><img src="{site-url}engine/images/icons/document-edit-icon.png" width="16" height="16" onmouseover="tooltip.show('ویرایش')" onmouseout="tooltip.hide()" /></a> <a href="javascript:post_delete({id})"><img src="{site-url}engine/images/icons/cross-script.png" width="16" height="16" onmouseover="tooltip.show('حذف')" onmouseout="tooltip.hide()" /></a></td>
  </tr>
  [/for posts]
</table>
[/posts]
[not-posts]{function name="message" args="هیچ پستی در سیستم یافت نشد!|info"}[/not-posts]
[not-ajax]
</div>

<div class="clear"></div>
</div>
</div>
<div class="content-bottom"></div>
</div>
<!-- TAB END-->
[/not-ajax]