[not-ajax]
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function file_ajax(id)
{
	if (id == 1)
	{
		apadana.$('new-files-form').reset();
		$('#new-files-form:hidden').slideDown('slow');
		$('#option-ajax-1').html('');
	}
	else if (id == 2)
	{
		$.ajax({
			type: 'get',
			url: '{admin-page}&module=files',
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
		apadana.changeTab(2, 3, function(){file_ajax(2)})
		alert('ابتدا یک فایل را برای ویرایش انتخاب کنید!');
	}
}
function file_list(url)
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
function file_info(ID)
{
	$.ajax({
		type: 'get',
		url: '{admin-page}&module=files&do=get-info&id='+ID,
		dataType: 'json',
		beforeSend: function()
		{
			apadana.loading(1);
		},
		success: function(result)
		{
			if (result.error)
			{
				if (result.error == 'not found')
				{
					alert('فایل مورد نظر در سیستم یافت نشد!')
				}
				else // access
				{
					alert('دسترسی لازم برای مشاهده اطلاعات فایل را ندارید!')
				}
				return false;
			}

			$('#file-info-box').html('<br/><br/>');
			$('#file-info-box').append('<center>تاریخ ثبت: '+result.file_date+'</center>');
			if (result.url)
			{
				$('#file-info-box').append('<center>لینک ثابت: <input type="text" value="'+result.url+'" onclick="this.select()" style="width:550px;margin:10px 0px;direction:ltr;text-align:center" /></center>');
			}
			if (result.urlSeo)
			{
				$('#file-info-box').append('<center>لینک سئو: <input type="text" value="'+result.urlSeo+'" onclick="this.select()" style="width:550px;margin:10px 0px;direction:ltr;text-align:center" /></center>');
			}

			apadana.infoBox('#file-info-box', 1);
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
function file_members(ID)
{
	$.ajax({
		type: 'get',
		url: '{admin-page}&module=files&do=get-members&id='+ID,
		dataType: 'json',
		beforeSend: function()
		{
			apadana.loading(1);
		},
		success: function(result)
		{
			if (result.error)
			{
				if (result.error == 'not found')
				{
					alert('فایل مورد نظر در سیستم یافت نشد!')
				}
				else // access
				{
					alert('دسترسی لازم برای مشاهده اطلاعات فایل را ندارید!')
				}
				return false;
			}

			$('#file-members-div').html(result.members);
			apadana.infoBox('#file-members-box', 1);
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
function file_url(val)
{
	var url = val.split('#');
	url[0] = apadana.trim(url[0]);
	url[0] = url[0].replace(/{my-url-1}/gi, '');
	url[0] = url[0].replace(/{my-url-2}/gi, '');
	return url[0];
}
function file_slug(ID, ID2)
{
	text = $(ID).val();
	text = text.split('/');
	text = text[text.length-1];
    text = apadana.slug(text);
	
	if (text == '')
	{
		alert('آدرس فایل را ننوشته اید!');
		return;
	}
	
	$(ID2).val(text);
}
function file_new()
{
	$('#files-url').val( file_url($('#files-url').val()) );
	if ($('#files-url').val() != '' && $('#files-slug').val() == '')
	{
		file_slug('#files-url', '#files-slug')
	}
	
	$.ajax({
		type: 'post',
		url: '{admin-page}&module=files&do=new',
		data: $('#new-files-form').serialize(),
		dataType: 'json',
		beforeSend: function()
		{
			$('#option-ajax-1:visible').slideUp('slow');
			apadana.loading(1);
		},
		success: function(result)
		{
			$('#files-slug').val(result.slug);
			$('#option-ajax-1').slideUp('slow', function(){
				$(this).html(apadana.message(result.message, result.type)).slideDown('slow');
				if (result.url)
				{
					$(this).append('<center>لینک ثابت: <input type="text" value="'+result.url+'" onclick="this.select()" readonly="readonly" style="width:700px;margin:10px 0px;direction:ltr;text-align:center" /></center>');
				}
				if (result.urlSeo)
				{
					$(this).append('<center>لینک سئو: <input type="text" value="'+result.urlSeo+'" onclick="this.select()" readonly="readonly" style="width:700px;margin:10px 0px;direction:ltr;text-align:center" /></center>');
				}
			})
			if (result.type == 'success')
			{
				$('#new-files-form').slideUp('slow');
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
function file_edit(ID)
{
	if (ID == 'save')
	{
		$('#edit-files-url').val( file_url($('#edit-files-url').val()) );
		if ($('#edit-files-url').val() != '' && $('#edit-files-slug').val() == '')
		{
			file_slug('#edit-files-url', '#edit-files-slug')
		}
	
		ID = $('#edit-files-id').val();
		$.ajax({
			type: 'post',
			url: '{admin-page}&module=files&do=edit&id='+ID,
			data: $('#edit-files-form').serialize(),
			dataType: 'json',
			beforeSend: function()
			{
				$('#option-ajax-3').slideUp('slow');
				apadana.loading(1);
			},
			success: function(result)
			{
				$('#edit-files-slug').val(result.slug);
				$('#option-ajax-3').slideUp('slow', function(){
					$(this).html(apadana.message(result.message, result.type)).slideDown('slow');
					if (result.url)
					{
						$(this).append('<center>لینک ثابت: <input type="text" value="'+result.url+'" onclick="this.select()" readonly="readonly" style="width:700px;margin:10px 0px;direction:ltr;text-align:center" /></center>');
					}
					if (result.urlSeo)
					{
						$(this).append('<center>لینک سئو: <input type="text" value="'+result.urlSeo+'" onclick="this.select()" readonly="readonly" style="width:700px;margin:10px 0px;direction:ltr;text-align:center" /></center>');
					}
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
			url: '{admin-page}&module=files&do=get-data&id='+ID,
			dataType: 'json',
			beforeSend: function()
			{
				$('#option-ajax-3').slideUp('slow');
				apadana.loading(1);
			},
			success: function(result)
			{
				if (result.error)
				{
					if (result.error == 'not found')
					{
						alert('فایل مورد نظر در سیستم یافت نشد!')
					}
					else // access
					{
						alert('دسترسی لازم برای ویرایش فایل را ندارید!')
					}
					return false;
				}

				apadana.changeTab(3, 3);
				$('#edit-files-id').val(result.file_id);
				$('#edit-files-url').val(result.file_url);
				$('#edit-files-slug').val(result.file_slug);
				
				for(var i = 0 ; i < apadana.$('edit-files-access').options.length; i++)
				{
					if (apadana.$('edit-files-access').options[i].value == result.file_access)
					{
						apadana.$('edit-files-access').options[i].selected = 'selected';
						break;
					}
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
function file_delete(ID)
{
    if (confirm('آیا از حذف این فایل اطمینان دارید؟'))
	$.ajax({
		type: 'get',
		url: '{admin-page}&module=files&do=delete',
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

<div id="file-info-box" style="display:none"></div>

<div id="file-members-box" style="display:none">
<div id="file-members-div" style="max-height:300px;overflow-y:scroll;margin-top:15px"></div>
</div>

<!-- TAB START-->
<div class="content">
<div class="content-tabs">
<ul>
  <li class="tab-on" id="tab-id-1" onclick="apadana.changeTab(1, 4, function(){file_ajax(1)})">فایل جدید</li>
  <li class="tab-off" id="tab-id-2" onclick="apadana.changeTab(2, 4, function(){file_ajax(2)})">فهرست فایل ها</li>
  <li class="tab-off" id="tab-id-3" onclick="apadana.changeTab(3, 4, function(){file_ajax(3)})">ویرایش فایل</li>
  <li class="tab-off" id="tab-id-4" onclick="apadana.changeTab(4, 4)">راهنما</li>
</ul>
</div>
<div class="content-main">
<div class="content-space">

<div id="option-id-1" style="display:block">
<div id="option-ajax-1"></div>
<form id="new-files-form" onsubmit="file_new(); return false">
<table cellspacing="7">
  <tr>
	<td style="width:115px">آدرس فایل</td>
	<td><input id="files-url" name="files[url]" type="text" value="" style="width:600px" dir="ltr" onblur="this.value=file_url(this.value);if(this.value != '' && $('#files-slug').val() == '') file_slug('#files-url', '#files-slug')" />&nbsp;<input type="button" value="رسانه ها" onclick="apadana.popupWindow('{admin-page}&section=media&noTemplate=true&input=files-url', 'media', 1000, 700)" /></td>
  </tr>
  <tr>
	<td>نام مستعار</td>
	<td><input id="files-slug" name="files[slug]" value="" type="text" dir="ltr" style="width:600px" />&nbsp;<input type="button" value="بساز" onclick="file_slug('#files-url', '#files-slug')" /></td>
  </tr>
  <tr>
	<td>دسترسی دانلود فایل</td>
	<td><select name="files[access]" size="1"><option value="1" selected="selected">برای همه</option><option value="2">فقط برای کاربران سایت</option><option value="3">فقط کاربران مهمان</option><option value="4">فقط مدیران سایت</option><option value="5">فقط مدیران کل سایت</option></select></td>
  </tr>
  <tr>
	<td colspan="2"><input type="submit" value="ثبت فایل جدید" />&nbsp;<input type="reset" value="پاک کردن فرم" /></td>
  </tr>
</table>
</form>
</div>
<!-- /option-id-1 -->
<div id="option-id-2" style="display:none">
[/not-ajax]
<form id="form-options-show" class="fast-panel">
چینش&nbsp;&raquo;&nbsp;
<select name="order" size="1">
<option value="DESC"[desc] selected="selected"[/desc]>نزولی</option>
<option value="ASC"[asc] selected="selected"[/asc]>صعودی</option>
</select>
&nbsp;&nbsp;تعداد فایل ها در صفحه&nbsp;&raquo;&nbsp;
<input name="total" type="text" style="width:25px;text-align:center" value="{total}" maxlength="3" />
&nbsp;&nbsp;جستجو&nbsp;&raquo;&nbsp;
<input name="search" type="text" style="width:150px" value="{search}" dir="ltr" />
[pages]
&nbsp;&nbsp;فایل&nbsp;&raquo;&nbsp;
<select name="page" size="1">
[for pages]<option value="{number}"[selected] selected="selected"[/selected]>{number}</option>[/for pages]
</select>
[/pages]
&nbsp;&nbsp;
<input type="button" value="نمایش" onclick="file_list('{admin-page}&module=files&'+apadana.serialize('form-options-show'))" />
</form>
[list]
<table class="apadana-table" cellpadding="0" cellspacing="0">
<thead>
  <tr>
	<th width="5">#</th>
	<th align="right">عنوان فایل</th>
	<th width="20">دانلودها</th>
	<th width="20">اطلاعات</th>
	<th width="20">کاربران</th>
	<th width="20">نویسنده</th>
	<th width="20">عملیات</th>
  </tr>
</thead>
<tbody>
[for list]
  <tr class="{odd-even}">
	<td>{id}</td>
	<td align="right"><span data-tooltip="ارسال شده در {past-time}" >{name}</span></td>
	<td><span data-tooltip="این فایل {count-downloads} بار دانلود شده است" >{count-downloads}</span></td>
	<td><a href="javascript:file_info({id})"><img src="{site-url}engine/images/icons/clipboard-list.png" width="16" height="16" data-tooltip="اطلاعات تکمیلی"  /></a></td>
	<td><a href="javascript:file_members({id})"><img src="{site-url}engine/images/icons/users.png" width="16" height="16" data-tooltip="مشاهده فهرست کاربرانی که این فایل را دانلود کرده اند"  /></a></td>
	<td>{author}</td>
	<td><a href="javascript:file_edit({id})"><img src="{site-url}engine/images/icons/document-edit-icon.png" width="16" height="16" data-tooltip="ویرایش"  /></a>&nbsp;<a href="javascript:file_delete({id})"><img src="{site-url}engine/images/icons/cross-script.png" width="16" height="16" data-tooltip="حذف"  /></a></td>
  </tr>
[/for list]
</tbody>
</table>
[/list]
[not-list]{function name="message" args="{msg}|info"}[/not-list]
[not-ajax]
</div>
<!-- /option-id-2 -->
<div id="option-id-3" style="display:none">
<div id="option-ajax-3"></div>
<form id="edit-files-form" onsubmit="file_edit('save'); return false">
<table cellspacing="7">
  <tr>
	<td style="width:115px">آدرس فایل</td>
	<td><input id="edit-files-url" name="files[url]" type="text" value="" style="width:600px" dir="ltr" onblur="this.value=file_url(this.value);if(this.value != '' && $('#edit-files-slug').val() == '') file_slug('#edit-files-url', '#edit-files-slug')" />&nbsp;<input type="button" value="رسانه ها" onclick="apadana.popupWindow('{admin-page}&section=media&noTemplate=true&input=edit-files-url', 'media', 1000, 700)" /></td>
  </tr>
  <tr>
	<td>نام مستعار</td>
	<td><input id="edit-files-slug" name="files[slug]" value="" type="text" dir="ltr" style="width:600px" />&nbsp;<input type="button" value="بساز" onclick="file_slug('#edit-files-url', '#edit-files-slug')" /></td>
  </tr>
  <tr>
	<td>دسترسی دانلود فایل</td>
	<td><select id="edit-files-access" name="files[access]" size="1"><option value="1">برای همه</option><option value="2">فقط برای کاربران سایت</option><option value="3">فقط کاربران مهمان</option><option value="4">فقط مدیران سایت</option><option value="5">فقط مدیران کل سایت</option></select></td>
  </tr>
  <tr>
	<td colspan="2"><input id="edit-files-id" type="hidden" value="0" /><input type="submit" value="ویرایش فایل" /></td>
  </tr>
</table>
</form>
</div>
<!-- /option-id-3 -->
<div id="option-id-4" style="display:none">
برای بعضی از فایل ها تمایل دارید که دسترسی دانلود آن را به گروه خاصی از کاربران محدود کنید و یا از دفعات دانلود آن آگاه شوید.<br/>
با استفاده از این بخش می توانید فایل خود در در فهرست فایل ها ثبت کنید، سپس سیستم یک آدرس مجازی از آن فایل تولید می کند که شما باید از آن آدرس
در مکان مورد نظر خود استفاده کنید، با این روش آدرس اصلی فایل شما مخفی خواهد ماند البته دقت کنید که فایل مذور باید روی هاست سایت شما قرار داشته باشد
و همچنین در این روش به دلیل انجام دانلود به صورت غیر مستقیم امکان توقف دانلود برای کاربران وجود نخواهد داشت.
</div>
<!-- /option-id-4 -->

<div class="clear"></div>
</div>
</div>
<div class="content-bottom"></div>
</div>
<!-- TAB END-->
[/not-ajax]
