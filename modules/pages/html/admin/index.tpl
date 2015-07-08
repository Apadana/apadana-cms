[not-ajax]
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function page_ajax(id)
{
	if (id == 1)
	{
		apadana.$('new-pages-form').reset();
		$('#new-pages-form:hidden').slideDown('slow');
		$('#option-ajax-1').html('');
		CKEDITOR.instances.textarea_pages_text.setData('');
	}
	else if (id == 2)
	{
		$.ajax({
			type: 'get',
			url: '{admin-page}&module=pages',
			beforeSend: function()
			{
				$('#option-id-2').html('<p><center><img src="{site-url}engine/images/loading/loader-9.gif" width="54" height="55"></center></p>');
			},
			success: function(data)
			{
				$('#option-id-2').slideUp('slow', function(){
					$(this).html(data).slideDown('slow');
				});
			},
			error: function()
			{
				alert('در ارتباط خطایی رخ داده است!');
			}
		})
	}
	else if (id == 3)
	{
		apadana.changeTab(2, 3, function(){page_ajax(2)})
		alert('ابتدا یک صفحه را برای ویرایش انتخاب کنید!');
	}
}
function page_list(url)
{
	$.ajax({
		type: 'get',
		url: url,
		beforeSend: function()
		{
			apadana.loading(1);
		},
		success: function(data)
		{
			$('#option-id-2').slideUp('slow', function(){
				$(this).html(data).slideDown('slow');
			});
		},
		error: function()
		{
			alert('در ارتباط خطایی رخ داده است!');
		},
		complete: function()
		{
			apadana.loading(0);
		}
	})
}
function page_slug(ID, ID2)
{
	text = $(ID).val();
    text = apadana.slug(text);
	
	if (text == '')
	{
		alert('عنوان صفحه را ننوشته اید!');
		return;
	}
	
	$(ID2).val(text);
}
function page_new()
{
	$('#textarea_pages_text').val( CKEDITOR.instances.textarea_pages_text.getData() );
	$.ajax({
		type: 'post',
		url: '{admin-page}&module=pages&do=new',
		data: $('#new-pages-form').serialize(),
		dataType: 'json',
		beforeSend: function()
		{
			$('#option-ajax-1:visible').slideUp('slow');
			apadana.loading(1);
		},
		success: function(result)
		{
			$('#pages-slug').val(result.slug);
			$('#option-ajax-1').slideUp('slow', function(){
				$(this).html(apadana.message(result.message, result.type)).slideDown('slow');
			});
			if (result.type == 'success')
			{
				$('#new-pages-form').slideUp('slow');
			}
		},
		error: function()
		{
			alert('در ارتباط خطایی رخ داده است!');
		},
		complete: function()
		{
			apadana.loading(0);
		}
	})
}
function page_edit(ID)
{
	if (ID == 'save')
	{
		ID = $('#edit-pages-id').val();
		$('#textarea_pages_text_edit').val( CKEDITOR.instances.textarea_pages_text_edit.getData() );
		$.ajax({
			type: 'post',
			url: '{admin-page}&module=pages&do=edit&id='+ID,
			data: $('#edit-pages-form').serialize(),
			dataType: 'json',
			beforeSend: function()
			{
				$('#option-ajax-3').slideUp('slow');
				apadana.loading(1);
			},
			success: function(result)
			{
				$('#edit-pages-slug').val(result.slug);
				$('#option-ajax-3').slideUp('slow', function(){
					$(this).html(apadana.message(result.message, result.type)).slideDown('slow');
				});
			},
			error: function()
			{
				alert('در ارتباط خطایی رخ داده است!');
			},
			complete: function()
			{
				apadana.loading(0);
			}
		})
	}
	else
	{
		$.ajax({
			type: 'get',
			url: '{admin-page}&module=pages&do=get-data&id='+ID,
			dataType: 'json',
			beforeSend: function()
			{
				$('#option-ajax-3:visible').slideUp('slow');
				apadana.loading(1);
			},
			success: function(result)
			{
				if (result.error)
				{
					if (result.error == 'not found')
					{
						alert('صفحه مورد نظر در سیستم یافت نشد!')
					}
					else // access
					{
						alert('دسترسی لازم برای ویرایش صفحه را ندارید!')
					}
					return false;
				}

				apadana.changeTab(3, 3);
				$('#edit-pages-id').val(result.page_id);
				$('#edit-pages-title').val(result.page_title);
				$('#edit-pages-slug').val(result.page_slug);
				CKEDITOR.instances.textarea_pages_text_edit.setData(result.page_text);
				
				for(var i = 0 ; i < apadana.$('edit-pages-view').options.length; i++)
				{
					if (apadana.$('edit-pages-view').options[i].value == result.page_view)
					{
						apadana.$('edit-pages-view').options[i].selected = 'selected';
						break;
					}
				}
				
				if (apadana.$('edit-pages-theme'))
				{
					apadana.$('edit-pages-theme').options[0].selected = 'selected';
					for(var i = 0 ; i < apadana.$('edit-pages-theme').options.length; i++)
					{
						if (apadana.$('edit-pages-theme').options[i].value == result.page_theme)
						{
							apadana.$('edit-pages-theme').options[i].selected = 'selected';
							break;
						}
					}
				}

				if (result.page_comment == 1)
				{
					apadana.$('edit-pages-comment-1').checked = 'checked';
					apadana.$('edit-pages-comment-0').checked = false;
				}
				else
				{
					apadana.$('edit-pages-comment-1').checked = false;
					apadana.$('edit-pages-comment-0').checked = 'checked';
				}
				
				if (result.page_approve == 1)
				{
					apadana.$('edit-pages-approve-1').checked = 'checked';
					apadana.$('edit-pages-approve-0').checked = false;
				}
				else
				{
					apadana.$('edit-pages-approve-1').checked = false;
					apadana.$('edit-pages-approve-0').checked = 'checked';
				}
			},
			error: function()
			{
				alert('در ارتباط خطایی رخ داده است!');
			},
			complete: function()
			{
				apadana.loading(0);
			}
		})
	}
}
function page_approve(ID)
{
	$.ajax({
		type: 'get',
		url: '{admin-page}&module=pages&do=approve',
		data: 'id='+ID,
		beforeSend: function()
		{
			apadana.loading(1);
		},
		success: function(result)
		{
			result = apadana.trim(result);
			if (result == 'active')
			{
				apadana.changeSrc('page-approve-'+ID, '{site-url}engine/images/icons/plus-button.png');
				apadana.attr('page-approve-'+ID, 'onmouseover','tooltip.show(\'منتشر شده\')');
			}
			else if (result == 'inactive')
			{
				apadana.changeSrc('page-approve-'+ID, '{site-url}engine/images/icons/minus-button.png');
				apadana.attr('page-approve-'+ID, 'onmouseover','tooltip.show(\'چرکنویس\')');
			}
			else
			{
				alert(result);
			}
		},
		error: function()
		{
			alert('در ارتباط خطایی رخ داده است!');
		},
		complete: function()
		{
			apadana.loading(0);
		}
	})
}
function page_delete(ID)
{
    if (confirm('آیا از حذف این صفحه اطمینان دارید؟'))
	$.ajax({
		type: 'get',
		url: '{admin-page}&module=pages&do=delete',
        data: 'id='+ID,
		beforeSend: function()
		{
			apadana.loading(1);
		},
		success: function(data)
		{
			$('#option-id-2').slideUp('slow', function(){
				$(this).html(data).slideDown('slow');
			});
		},
		error: function()
		{
			alert('در ارتباط خطایی رخ داده است!');
		},
		complete: function()
		{
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
  <li class="tab-on" id="tab-id-1" onclick="apadana.changeTab(1, 3, function(){page_ajax(1)})">صفحه جدید</li>
  <li class="tab-off" id="tab-id-2" onclick="apadana.changeTab(2, 3, function(){page_ajax(2)})">فهرست صفحات</li>
  <li class="tab-off" id="tab-id-3" onclick="apadana.changeTab(3, 3, function(){page_ajax(3)})">ویرایش صفحه</li>
  <li class="tab-off" id="tab-id-4" onclick="apadana.location('{admin-page}&section=comments&type=pages')">نظرات</li>
</ul>
</div>
<div class="content-main">
<div class="content-space">

<div id="option-id-1" style="display:block">
<div id="option-ajax-1"></div>
<form id="new-pages-form" onsubmit="page_new(); return false">
<table cellspacing="7">
  <tr>
	<td width="100">عنوان صفحه</td>
	<td><input id="pages-title" name="pages[title]" type="text" value="" lang="fa-IR" style="width:70%" /></td>
  </tr>
  <tr>
	<td>نام مستعار</td>
	<td><input id="pages-slug" name="pages[slug]" value="" type="text" dir="ltr" style="width:70%" /> <input type="button" value="بساز" onclick="page_slug('#pages-title', '#pages-slug')" /></td>
  </tr>
  <tr>
	<td colspan="2" width="100">{textarea}</td>
  </tr>
  <tr>
	<td>نمایش صفحه</td>
	<td><select name="pages[view]" size="1"><option value="1" selected="selected">برای همه</option><option value="2">فقط برای کاربران سایت</option><option value="3">فقط کاربران مهمان</option><option value="4">فقط مدیران سایت</option><option value="5">فقط مدیران کل سایت</option></select></td>
  </tr>
  <tr>
	<td>استایل صفحه</td>
	<td>[templates]<select name="pages[theme]" size="1">[for templates]<option value="{name}">{name}</option>[/for templates]</select>[/templates][not-templates]تم فعلی سایت این بخش را پشتیبانی نمی کند![/not-templates]</td>
  </tr>
  <tr>
	<td>ارسال نظر</td>
	<td><label><input type="radio" name="pages[comment]" value="1" checked="checked" />امکان ارسال نظر برای این صفحه فعال باشد</label> <label><input type="radio" name="pages[comment]" value="0" />غیرفعال</label></td>
  </tr>
  <tr>
	<td>وضعیت صفحه</td>
	<td><label><input type="radio" name="pages[approve]" value="1" checked="checked" />فعال</label> <label><input type="radio" name="pages[approve]" value="0" />چرکنویس</label></td>
  </tr>
  <tr>
	<td colspan="2"><input type="submit" value="ساختن صفحه" />&nbsp;<input type="reset" value="پاک کردن فرم" onClick="CKEDITOR.instances.textarea_pages_text.setData('')" /></td>
  </tr>
</table>
</form>
</div>
<!-- /option-id-1 -->
<div id="option-id-2" style="display:none">
[/not-ajax]
[list]
<form id="form-options-show" class="fast-panel">
چینش&nbsp;&raquo;&nbsp;
<select name="order" size="1">
<option value="DESC"[desc] selected="selected"[/desc]>نزولی</option>
<option value="ASC"[asc] selected="selected"[/asc]>صعودی</option>
</select>
&nbsp;&nbsp;تعداد آیتم ها در صفحه&nbsp;&raquo;&nbsp;
<input name="total" type="text" style="width:25px;text-align:center" value="{total}" maxlength="3" />
[pages]
&nbsp;&nbsp;صفحه&nbsp;&raquo;&nbsp;
<select name="page" size="1">
[for pages]<option value="{number}"[selected] selected="selected"[/selected]>{number}</option>[/for pages]
</select>
[/pages]
&nbsp;&nbsp;
<input type="button" value="نمایش" onclick="page_list('{admin-page}&module=pages&'+apadana.serialize('form-options-show'))" />
</form>
<table class="apadana-table" cellpadding="0" cellspacing="0">
<thead>
  <tr>
	<th width="5">#</th>
	<th align="right">عنوان صفحه</th>
	<th width="20">نظرات</th>
	<th width="20">مشاهده</th>
	<th width="20">وضعیت</th>
	<th width="20">نویسنده</th>
	<th width="20">عملیات</th>
  </tr>
</thead>
<tbody>
[for list]
  <tr class="{odd-even}">
	<td>{id}</td>
	<td align="right"><span onmouseover="tooltip.show('ارسال شده در {past-time}')" onmouseout="tooltip.hide()">{title}</span></td>
	<td><span onmouseover="tooltip.show('{comment-count} نظر تایید شده برای این صفحه')" onmouseout="tooltip.hide()">{comment-count}</span></td>
	<td><a href="{page-url}" target="_blank"><img src="{site-url}engine/images/icons/cursor.png" width="16" height="16" onmouseover="tooltip.show('مشاهده صفحه')" onmouseout="tooltip.hide()" /></a></td>
	<td><a href="javascript:page_approve({id})"><img src="{site-url}engine/images/icons/[approve]tick-button[/approve][not-approve]minus-button[/not-approve].png" width="16" height="16" onmouseover="tooltip.show('[approve]منتشر شده[/approve][not-approve]چرکنویس[/not-approve]')" onmouseout="tooltip.hide()" id="page-approve-{id}" /></a></td>
	<td>{author}</td>
	<td><a href="javascript:page_edit({id})"><img src="{site-url}engine/images/icons/document-edit-icon.png" width="16" height="16" onmouseover="tooltip.show('ویرایش')" onmouseout="tooltip.hide()" /></a>&nbsp;<a href="javascript:page_delete({id})"><img src="{site-url}engine/images/icons/cross-script.png" width="16" height="16" onmouseover="tooltip.show('حذف')" onmouseout="tooltip.hide()" /></a></td>
  </tr>
[/for list]
</tbody>
</table>
[/list]
[not-list]{function name="message" args="هیچ صفحه ای ساخته نشده است!|info"}[/not-list]
[not-ajax]
</div>
<!-- /option-id-2 -->
<div id="option-id-3" style="display:none">
<div id="option-ajax-3"></div>
<form id="edit-pages-form" onsubmit="page_edit('save'); return false">
<table cellspacing="7">
  <tr>
	<td width="100">عنوان صفحه</td>
	<td><input name="pages[title]" id="edit-pages-title" type="text" value="" lang="fa-IR" style="width:70%" /></td>
  </tr>
  <tr>
	<td>نام مستعار</td>
	<td><input id="edit-pages-slug" name="pages[slug]" value="" type="text" dir="ltr" style="width:70%" /> <input type="button" value="بساز" onclick="page_slug('#edit-pages-title', '#edit-pages-slug')" /></td>
  </tr>
  <tr>
	<td colspan="2" width="100">{textarea-edit}</td>
  </tr>
  <tr>
	<td>نمایش صفحه</td>
	<td><select name="pages[view]" id="edit-pages-view" size="1"><option value="1">برای همه</option><option value="2">فقط برای کاربران سایت</option><option value="3">فقط کاربران مهمان</option><option value="4">فقط مدیران سایت</option><option value="5">فقط مدیران کل سایت</option></select></td>
  </tr>
  <tr>
	<td>استایل صفحه</td>
	<td>[templates]<select name="pages[theme]" id="edit-pages-theme" size="1">[for templates]<option value="{name}">{name}</option>[/for templates]</select>[/templates][not-templates]تم فعلی سایت این بخش را پشتیبانی نمی کند![/not-templates]</td>
  </tr>
  <tr>
	<td>ارسال نظر</td>
	<td><label><input type="radio" name="pages[comment]" id="edit-pages-comment-1" value="1" />امکان ارسال نظر برای این صفحه فعال باشد</label> <label><input type="radio" name="pages[comment]"id="edit-pages-comment-0" value="0" />غیرفعال</label></td>
  </tr>
  <tr>
	<td>وضعیت صفحه</td>
	<td><label><input type="radio" name="pages[approve]" id="edit-pages-approve-1" value="1" />فعال</label> <label><input type="radio" name="pages[approve]" id="edit-pages-approve-0" value="0" />چرکنویس</label></td>
  </tr>
  <tr>
	<td colspan="2"><input id="edit-pages-id" type="hidden" value="0" /><input type="submit" value="ویرایش صفحه" /></td>
  </tr>
</table>
</form>
</div>
<!-- /option-id-3 -->

<div class="clear"></div>
</div>
</div>
<div class="content-bottom"></div>
</div>
<!-- TAB END-->
[/not-ajax]
