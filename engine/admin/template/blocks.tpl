[show-list]
[list]
<table class="apadana-table" cellpadding="0" cellspacing="0">
  <tr>
    <th width="5">#</th>
    <th align="right">عنوان بلوک</th>
    <th width="100">موقعیت</th>
    <th width="60">جابجایی</th>
    <th width="30">وضعیت</th>
    <th width="30">عملیات</th>
  </tr>
[for list]
  <tr class="{odd-even}" id="block-{id}">
    <td>{id}</td>
    <td align="right" id="block-title-{id}">{title}</td>
    <td>{position}</td>
    <td>[reposit]{reposit}[/reposit][not-reposit]<img src="{site-url}engine/images/icons/slash-button.png" width="16" height="16" onmouseover="tooltip.show('این بلوک را نمی توان جابجا کرد')" onmouseout="tooltip.hide()" />[/not-reposit]</td>
    <td><a href="javascript:void(0)" onclick="block_status({id})"><img src="{site-url}engine/images/icons/[status]plus-button[/status][not-status]minus-button[/not-status].png" width="16" height="16" onmouseover="tooltip.show('[status]فعال[/status][not-status]غیرفعال[/not-status]')" onmouseout="tooltip.hide()" id="block-status-{id}" /></a></td>
    <td><a href="javascript:void(0)" onclick="block_edit({id})"><img src="{site-url}engine/images/icons/document-edit-icon.png" width="16" height="16" onmouseover="tooltip.show('ویرایش')" onmouseout="tooltip.hide()" /></a> <a href="javascript:void(0)" onclick="block_delete({id})"><img src="{site-url}engine/images/icons/cross-script.png" width="16" height="16" onmouseover="tooltip.show('حذف')" onmouseout="tooltip.hide()" /></a></td>
  </tr>
[/for list]
</table>
[/list]
[not-list]{function name="message" args="هیچ بلوکی وجود ندارد!|info"}[/not-list]
[/show-list]
[not-show-list]
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
var list_update = true;
function block_ajax(id)
{
	if (id == 1)
	{
		$('#option-ajax-1:visible').slideUp('slow')
		$('#form-new-block:hidden').slideDown('slow');
		apadana.$('form-new-block').reset();
		$('#textarea_block_content').val('');
		CKEDITOR.instances.textarea_block_content.setData('');
	}
	else if (id == 2)
	{
		if (!list_update)
		{
			return false;
		}
		$.ajax({
			type: 'get',
			url: '{admin-page}&section=blocks&do=list',
			beforeSend: function()
			{
				$('#option-id-2').html('<p><center><img src="{site-url}engine/images/loading/loader-9.gif" width="54" height="55"></center></p>').fadeIn('fast')
			},
			success: function(data)
			{
				list_update = false;
				$('#option-id-2').slideUp('slow', function(){
					$(this).html(data).slideDown('slow');
				});
			},
			error: function()
			{
				alert('در ارتباط خطايي رخ داده است!');
			}
		})
	}
	else if (id == 3)
	{
		apadana.changeTab(2, 4, function(){block_ajax(2)})
		alert('ابتدا یک بلوک را برای ویرایش انتخاب کنید!');
	}
}
function block_new()
{
	$('#textarea_block_content').val( CKEDITOR.instances.textarea_block_content.getData() );
	$.ajax({
		type: 'post',
		url: '{admin-page}&section=blocks&do=new',
		data: $('#form-new-block').serialize(),
		dataType: 'json',
		beforeSend: function()
		{
			$('#option-ajax-1:visible').slideUp('slow');
			apadana.loading(1);
		},
		success: function(result)
		{
			list_update = true;
			$('#option-ajax-1').slideUp('slow', function(){
				$(this).html(apadana.message(result.message, result.type)).slideDown('slow');
			});
			if (result.type == 'success')
			{
				$('#form-new-block').slideUp('slow');
			}
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
function block_edit(ID)
{
	if (ID == 'save')
	{
		$('#textarea_block_content2').val(CKEDITOR.instances.textarea_block_content2.getData());
		ID = $('#block-edit-id').val();

		$.ajax({
			type: 'post',
			url: '{admin-page}&section=blocks&do=edit&id='+ID,
			data: $('#form-edit-block').serialize(),
			dataType: 'json',
			beforeSend: function()
			{
				$('#option-ajax-3').slideUp('slow');
				apadana.loading(1);
			},
			success: function(result)
			{
				list_update = true;
				$('#option-ajax-3').slideUp('slow', function(){
					$(this).html(apadana.message(result.message, result.type)).slideDown('slow');
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
	else
	{
		$('#option-ajax-3:visible').slideUp('slow');
		$.ajax({
			type: 'get',
			url: '{admin-page}&section=blocks&do=get-data&id='+ID,
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
						alert('بلوک مورد نظر در سیستم یافت نشد!')
					}
					return false;
				}

				apadana.changeTab(3, 4)
				$('#block-edit-id').val(result.block_id);
				$('#block-edit-title').val(result.block_title);
				$('#block-edit-function').val(result.block_function);
				$('#block-edit-access').val(result.block_access);
				CKEDITOR.instances.textarea_block_content2.setData(result.block_content);

				if (result.block_access_type == 1)
				{
					apadana.$('block-edit-access-type-1').checked = 'checked';
					apadana.$('block-edit-access-type-0').checked = false;
				}
				else
				{
					apadana.$('block-edit-access-type-1').checked = false;
					apadana.$('block-edit-access-type-0').checked = 'checked';
				}
				for (var i = 0 ; i < apadana.$('block-edit-position').options.length; i++)
				{
					if (apadana.$('block-edit-position').options[i].value == result.block_position)
					{
						apadana.$('block-edit-position').options[i].selected = 'selected';
						break;
					}
				}
				for (var i = 0 ; i < apadana.$('block-edit-view').options.length; i++)
				{
					if (apadana.$('block-edit-view').options[i].value == result.block_view)
					{
						apadana.$('block-edit-view').options[i].selected = 'selected';
						break;
					}
				}
				for (var i = 0 ; i < apadana.$('block-edit-active').options.length; i++)
				{
					if (apadana.$('block-edit-active').options[i].value == result.block_active)
					{
						apadana.$('block-edit-active').options[i].selected = 'selected';
						break;
					}
				}
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
}
function block_access(val)
{
	var pages = new Array();
	var p = val.split("\n");

	for (i = 0; i < p.length; i++)
	{
		var page = p[i].split('#');
		page[0] = decodeURIComponent(page[0]);
		page[0] = page[0].replace(/{my-url-1}/gi, '');
		page[0] = page[0].replace(/{my-url-2}/gi, '');

		if (page[0] != '/')
		{
			page[0] = page[0].replace(/^\/+|\/+$/g, '');
		}
		if (page[0] == '')
		{
			continue;
		}

		pages.push(page[0]);
	}

	return pages.join("\n");
}
function block_status(ID)
{
	apadana.ajax({
		method: 'get',
		action: '{admin-page}&section=blocks&do=status',
		data: 'id='+ID,
		success: function(status)
		{
			status = apadana.trim(status);
			if (status == 'active')
			{
				apadana.changeSrc('block-status-'+ID, '{site-url}engine/images/icons/plus-button.png');
				apadana.attr('block-status-'+ID, 'onmouseover','tooltip.show(\'فعال\')');
			}
			else if (status == 'inactive')
			{
				apadana.changeSrc('block-status-'+ID, '{site-url}engine/images/icons/minus-button.png');
				apadana.attr('block-status-'+ID, 'onmouseover','tooltip.show(\'غیرفعال\')');
			}
			else
			{
				alert(status);
			}
		}
	})
}
function block_reposit(type, ordering)
{
	$.ajax({
		type: 'get',
		url: '{admin-page}&section=blocks&do=reposit',
		data: 'type='+type+'&ordering='+ordering,
		beforeSend: function()
		{
			apadana.loading(1);
		},
		success: function(data)
		{
			$('#option-id-2').html(data);
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
function block_delete(ID)
{
	if (confirm("آیا از حذف این بلوک اطمینان دارید؟\n"+$('#block-title-'+ID).html()))
	$.ajax({
		type: 'get',
		url: '{admin-page}&section=blocks&do=delete',
		data: 'id='+ID,
		beforeSend: function()
		{
			apadana.loading(1);
		},
		success: function(data)
		{
			$('#option-id-2').slideUp('slow', function(){
				$('#option-id-2').html(data).slideDown('slow')
			})
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

<!-- TAB START-->
<div class="content">
<div class="content-tabs">
<ul>
  <li class="tab-on" id="tab-id-1" onclick="apadana.changeTab(1, 4, function(){block_ajax(1)})">بلوک جدید</li>
  <li class="tab-off" id="tab-id-2" onclick="apadana.changeTab(2, 4, function(){block_ajax(2)})">مدیریت بلوک ها</li>
  <li class="tab-off" id="tab-id-3" onclick="apadana.changeTab(3, 4, function(){block_ajax(3)})">ویرایش بلوک</li>
  <li class="tab-off" id="tab-id-4" onclick="apadana.changeTab(4, 4)">راهنما</li>
</ul>
</div>
<div class="content-main">
<div class="content-space">

<div id="option-id-1" style="display:block">
<div id="option-ajax-1"></div>
<form id="form-new-block" onsubmit="block_new();return false">
<table cellpadding="6" cellspacing="0">
  <tr>
	<td style="width:90px">عنوان</td>
	<td><input id="block-title" name="block[title]" type="text" style="width:400px" lang="fa" /></td>
  </tr>
  <tr>
	<td onmouseover="tooltip.show('Function')" onmouseout="tooltip.hide()">تابع بلوک</td>
	<td><input id="block-function" name="block[function]" type="text" style="width:400px" dir="ltr" /> <font color="#BBBBBB" size="1">(اختیاری)</font></td>
  </tr>
  <tr>
	<td colspan="2" width="90">{textarea}</td>
  </tr>
  <tr>
	<td>اجازه نمایش</td>
	<td><textarea name="block[access]" style="width:748px;height:80px;direction:ltr;text-align:left" onchange="this.value = block_access(this.value)"></textarea></td>
  </tr>
  <tr>
	<td>نوع نمایش</td>
	<td>
		<label><input name="block[access-type]" type="radio" value="1" checked="checked" />&nbsp;این بلوک فقط در صفحات مشخص شده نمایش داده شود.</label><br/>
		<label><input name="block[access-type]" type="radio" value="0" />&nbsp;این بلوک در تمامی صفحات بجز صفحات مشخص شده نمایش داده شود.</label>
	</td>
  </tr>
  <tr>
	<td>موقعیت</td>
	<td>{position}</td>
  </tr>
  <tr>
	<td>قابل نمایش برای</td>
	<td><select id="block-view" name="block[view]" size="1"><option value="1" selected="selected">همه کاربران</option><option value="2">فقط کاربران عضو سایت</option><option value="3">فقط کاربران غیر عضو سایت</option><option value="4">فقط مدیران سایت</option><option value="5">فقط مدیر کل سایت</option></select></td>
  </tr>
  <tr>
	<td>وضعیت</td>
	<td><select id="block-active" name="block[active]" size="1"><option value="1" selected="selected">فعال</option><option value="0">غیرفعال</option></select></td>
  </tr>
  <tr>
	<td colspan="2"><input type="submit" value="ذخیره بلوک" /> &nbsp; <input type="reset" value="از نو" onclick="CKEDITOR.instances.textarea_block_content.setData('')" /></td>
  </tr>
</table>
</form>
</div>
<!-- /option-id-1 -->
<div id="option-id-2" style="display:none"></div>
<!-- /option-id-2 -->
<div id="option-id-3" style="display:none">
<div id="option-ajax-3"></div>
<form id="form-edit-block" onsubmit="block_edit('save');return false">
<table cellpadding="6" cellspacing="0">
  <tr>
	<td style="width:90px">عنوان</td>
	<td><input id="block-edit-title" name="block[title]" type="text" style="width:400px" lang="fa" /></td>
  </tr>
  <tr>
	<td onmouseover="tooltip.show('Function')" onmouseout="tooltip.hide()">تابع بلوک</td>
	<td><input id="block-edit-function" name="block[function]" type="text" style="width:400px" dir="ltr" /> <font color="#BBBBBB" size="1">(اختیاری)</font></td>
  </tr>
  <tr>
	<td colspan="2" width="90">{edit-textarea}</td>
  </tr>
  <tr>
	<td>اجازه نمایش</td>
	<td><textarea id="block-edit-access" name="block[access]" style="width:748px;height:80px;direction:ltr;text-align:left" onchange="this.value = block_access(this.value)"></textarea></td>
  </tr>
  <tr>
	<td>نوع نمایش</td>
	<td>
		<label><input name="block[access-type]" type="radio" value="1" id="block-edit-access-type-1" />&nbsp;این بلوک فقط در صفحات مشخص شده نمایش داده شود.</label><br/>
		<label><input name="block[access-type]" type="radio" value="0" id="block-edit-access-type-0" />&nbsp;این بلوک در تمامی صفحات بجز صفحات مشخص شده نمایش داده شود.</label>
	</td>
  </tr>
  <tr>
	<td>موقعیت</td>
	<td>{edit-position}</td>
  </tr>
  <tr>
	<td>قابل نمایش برای</td>
	<td><select id="block-edit-view" name="block[view]" size="1"><option value="1">همه کاربران</option><option value="2">فقط کاربران عضو سایت</option><option value="3">فقط کاربران غیر عضو سایت</option><option value="4">فقط مدیران سایت</option><option value="5">فقط مدیر کل سایت</option></select></td>
  </tr>
  <tr>
	<td>وضعیت</td>
	<td><select id="block-edit-active" name="block[active]" size="1"><option value="1">فعال</option><option value="0">غیرفعال</option></select></td>
  </tr>
  <tr>
	<td colspan="2"><input id="block-edit-id" type="hidden" value="0" /><input type="submit" name="submit" value="ویرایش بلوک" /></td>
  </tr>
</table>
</form>
</div>
<!-- /option-id-3 -->
<div id="option-id-4" style="display:none">
<strong>راهنمای استفاده از بخش اجازه نمایش</strong><br />
با استفاده از این امکان می توانید یک بلوک را در یک یا چند صفحه خاص نمایش دهید برای این کار در هر خط از این فیلد یک آدرس قرار دهید و نوع نمایش را انتخاب کنید.<br />
در این حالت فقط صفحات مشخص شده برای نمایش و یا عدم نمایش یک بلوک استفاده می شوند، در صورتی که شما بخواهید دسترسی مشخص شده برای بلوک در آن آدرس و تمام آدرس های زیر مجموعه آن اعمال شود به انتهای آدرس علامت * را اضافه کنید<br />
به طور مثال در صورتی که لینک های سئو سایت فعال باشد آدرس سامانه کاربری به این صورت است: <b dir="ltr">account{file-rewrite}</b><br />
در صورتی که دسترسی بلوک را در آدرس <b dir="ltr">account{file-rewrite}</b> مسدود کنید بلوک فقط در این آدرس نمایش داده نمی شود ولی اکر آدرس را به صورت <b dir="ltr">account*</b> وارد کنید بلوک
در کل بخش سامانه کاربری نمایش داده نخواهد شد.

<br /><br /><br />
<strong>راهنمای استفاده از بخش تابع بلوک</strong><br />
در بعضی از بلوک ها شاید قصد نمایش متن را نداشته باشد به طور مثال بخواهید به جای متن موضوعات پست های سایت
در آن بلوک نمایش داده شود امکان تابع بلوک برای رفع این نیاز طراحی شده است.<br />
هر ماژول می تواند بی نهایت تابع برای استفاده در بلوک ها داشته باشد و سازنده ماژول نام توابع را از قبل به شما معرفی خواهد کرد.<br />
شما با قرار دادن نام تابع در فیلد مربوط آن در فرم ویرایش و یا ساختن بلوک جدید
می تواند موضوعات پست ها و یا هر چیز دیگری را به جای متن بلوک در سایت خود نمایش دهید.<br />
طبیعی است که در صورت قرار دادن یک تابع برای بلوک دیگر متن آن بلوک نمایش داده نخواهد شد.<br />
یکی از نکات مهم در توابع تنظیمات اختصاصی است که هر تابع می تواند داشته باشد و برای اعمال آن ها باید کد مخصوصی را به جای متن بلوک قرار دهیم،<br />
زمانی که قصد استفاده از تنظیمات بلوک را داریم فقط باید کدهای تنظیمات بلوک را در ادیتور متن قرار دهیم و هیچ چیز دیگری را ننویسیم!<br />
این کدها با کد <span style="color:#CC0000">[- options -]</span> در ابتدا شروع می شود، با استفاده از این کد سیستم متوجه می شود که محتوای بلوک مربوط به تنظیمات اختصای تابع انتخاب شده برای آن است.<br />
سپس در هر خط بعدی یک دستور قرار می گیرد و آن را مساوی مقدار مورد نظر قرار می دهیم، برای فهم بهتر کدهای زیر یک نمونه از تنظیمات اختصاصی است که مطعلق به تابع <strong>last_posts</strong> می باشد:<br />
<div style="text-align:left;direction:ltr;font-weight:bold; background: #DDDDDD; padding: 5px; margin: 20px">
<span style="color:#CC0000">[- options -]</span><br />
<span style="color:#009933">total</span> <span style="color:#3366CC">=</span> <span style="color:#9900CC">10</span><br />
<span style="color:#009933">hits</span> <span style="color:#3366CC">=</span> <span style="color:#9900CC">true</span><br />
<span style="color:#009933">order</span> <span style="color:#3366CC">=</span> <span style="color:#9900CC">desc</span>
</div>
درنمونه کد بالا <strong>total</strong> مشخص کننده تعداد لینک ها است و <strong>hits</strong> مشخص می کند که پست ها بر اساس دفعات نماش فهرست شوند و
<strong>order</strong> نوع چینش لینک ها را مشخص می کند.<br /><br />
در صورتی که اولین بار کدهای تنظیمات را در ادیتور به جای محتوا قرار می دهید به دلیل راست چین بودن ادیتور ممکن است با مشکل مواجه شود در این حالت روی دکمه <b>منبع</b> موجود در ادیتور کلیک کنید تا حالت نمایش کدها فعال شود و سپس کد خود را قرار دهید.<br />
دقت کنید که در این حالت در پایان هر خط باید کد <strong>&lt;br&gt;</strong> را قرار دهید
<br /><br />
در فهرست زیر توابع پیشفرض آپادانا برای استفاده در بلوک ها قرار داده شده است برای مشاهده توضیحات بیشتر در باره هر کدام روی نام آنها کلیک کنید:
<br /><br />

<style>
.list-func {
	font-weight: bold;
	font-family: Arial;
	background: #0080c0;
	color: #FFFFFF;
	padding: 3px 5px;
	margin-bottom: 2px;
	direction: ltr;
	cursor: pointer;
}
.list-func:hover {
	background: #008040;
}
.content-func {
	background: #f0f8ff;
	border: #b7ddff 1px solid;
	color: #003399;
	cursor: default;
	margin-bottom: 2px;
	padding: 3px;
}
</style>

<div class="list-func" onclick="$('#show-account').slideToggle('slow')">account</div>
<div id="show-account" class="content-func" style="display:none">
این تابع اطلاعات سامانه کاربری را به صورت پیشرفته نمایش می دهد و تنظیمات اختصاصی ندارد.
</div>

<div class="list-func" onclick="$('#show-login').slideToggle('slow')">login</div>
<div id="show-login" class="content-func" style="display:none">
این تابع اطلاعات سامانه کاربری را به صورت ساده نمایش می دهد و تنظیمات اختصاصی ندارد.
</div>

<div class="list-func" onclick="$('#show-onlines').slideToggle('slow')">onlines</div>
<div id="show-onlines" class="content-func" style="display:none">
این تابع فهرست کاربران آنلاین را نمایش می دهد و تنظیمات اختصاصی ندارد.
</div>

<div class="list-func" onclick="$('#show-pages').slideToggle('slow')">pages</div>
<div id="show-pages" class="content-func" style="display:none">
این تابع فهرست صفحات جانبی را نمایش می دهد و تنظیمات اختصاصی دارد.
<div style="text-align:left;direction:ltr;font-weight:bold;margin:4px">
<span style="color:#CC0000">[- options -]</span><br />
<span style="color:#009933">total</span> <span style="color:#3366CC">=</span> <span style="color:#9900CC">15</span><br />
<span style="color:#009933">order</span> <span style="color:#3366CC">=</span> <span style="color:#9900CC">desc</span>
</div>
total تعداد لینک ها را تعیین می کند و order نوع چینش لینک ها را.<br />
order فقط می تواند دارای مقدارهای desc و یا asc باشد و اگر تعیین نشود به صورت پیشفرض desc در نظر گرفته خواهد شد.<br />
desc به معنای چینش نزولی است و asc صعودی.<br />
</div>

<div class="list-func" onclick="$('#show-tags_cloud').slideToggle('slow')">tags_cloud</div>
<div id="show-tags_cloud" class="content-func" style="display:none">
این تابع فهرست ابری برچسب های پست ها را نمایش می دهد و تنظیمات اختصاصی دارد.
<div style="text-align:left;direction:ltr;font-weight:bold;margin:4px">
<span style="color:#CC0000">[- options -]</span><br />
<span style="color:#009933">total</span> <span style="color:#3366CC">=</span> <span style="color:#9900CC">50</span><br />
</div>
total تعداد برچسب ها را تعیین می کند و به صورت پیشفرض دارای عدد 50 می باشد.
</div>

<div class="list-func" onclick="$('#show-categories').slideToggle('slow')">categories</div>
<div id="show-categories" class="content-func" style="display:none">
این تابع موضوعات پست ها را نمایش می دهد و تنظیمات اختصاصی ندارد.
</div>		

<div class="list-func" onclick="$('#show-last_posts').slideToggle('slow')">last_posts</div>
<div id="show-last_posts" class="content-func" style="display:none">
این تابع فهرست پست ها را نمایش می دهد و تنظیمات اختصاصی دارد.
<div style="text-align:left;direction:ltr;font-weight:bold;margin:4px">
<span style="color:#CC0000">[- options -]</span><br />
<span style="color:#009933">total</span> <span style="color:#3366CC">=</span> <span style="color:#9900CC">15</span><br />
<span style="color:#009933">hits</span> <span style="color:#3366CC">=</span> <span style="color:#9900CC">false</span><br />
<span style="color:#009933">order</span> <span style="color:#3366CC">=</span> <span style="color:#9900CC">desc</span>
</div>
total تعداد لینک ها را تعیین می کند و فقط می تواند مقدار عددی داشته باشد و hits نوع چینش را براساس دفعات نمایش قرار می دهد و order نوع چینش لینک ها را مشخص می کند که نزولی باشد و یا صعودی.<br />
order فقط می تواند دارای مقدارهای desc و یا asc باشد و اگر تعیین نشود به صورت پیشفرض desc در نظر گرفته خواهد شد.<br />
desc به معنای چینش نزولی است و asc صعودی.<br />
در صورتی که hits دارای مقدار true باشد چینش بر اساس دفعات نمایش خواهد بود و در غیر اینصورت بر اساس تاریخ انتشار پست.
</div>		

<div class="list-func" onclick="$('#show-posts_calendar').slideToggle('slow')">posts_calendar</div>
<div id="show-posts_calendar" class="content-func" style="display:none">
با این تابع شما می توانید یک تقویم رومیزی را نشان دهید. این تقویم شامل پست ها هم می شود.
</div>

<div class="list-func" onclick="$('#show-posts_comments').slideToggle('slow')">posts_comments</div>
<div id="show-posts_comments" class="content-func" style="display:none">
این تابع فهرست نظرات پست ها را نمایش می دهد و تنظیمات اختصاصی دارد.
<div style="text-align:left;direction:ltr;font-weight:bold;margin:4px">
<span style="color:#CC0000">[- options -]</span><br />
<span style="color:#009933">total</span> <span style="color:#3366CC">=</span> <span style="color:#9900CC">15</span><br />
<span style="color:#009933">order</span> <span style="color:#3366CC">=</span> <span style="color:#9900CC">desc</span>
</div>
total تعداد لینک ها را تعیین می کند و فقط می تواند مقدار عددی داشته باشد و order نوع چینش لینک ها را مشخص می کند که نزولی باشد و یا صعودی.<br />
order فقط می تواند دارای مقدارهای desc و یا asc باشد و اگر تعیین نشود به صورت پیشفرض desc در نظر گرفته خواهد شد.<br />
desc به معنای چینش نزولی است و asc صعودی.<br />
</div>		

<div class="list-func" onclick="$('#show-search').slideToggle('slow')">search</div>
<div id="show-search" class="content-func" style="display:none">
این تابع فرم جستجو را نمایش می دهد و تنظیمات اختصاصی دارد.
<div style="text-align:left;direction:ltr;font-weight:bold;margin:4px">
<span style="color:#CC0000">[- options -]</span><br />
<span style="color:#009933">size</span> <span style="color:#3366CC">=</span> <span style="color:#9900CC">150</span><br />
</div>
size اندازه فیلد جستجو را مشخص می کند و فقط دارای مقدار عددی می تواند باشد.
</div>		

<div class="list-func" onclick="$('#show-shoutbox').slideToggle('slow')">shoutbox</div>
<div id="show-shoutbox" class="content-func" style="display:none">
این تابع جعبه پیام کوتاه را نمایش می دهد و تنظیمات اختصاصی ندارد.
</div>		

<div class="list-func" onclick="$('#show-simple_links').slideToggle('slow')">simple_links</div>
<div id="show-simple_links" class="content-func" style="display:none">
این تابع فهرست لینک ها را نمایش می دهد و تنظیمات اختصاصی ندارد.
</div>		

<div class="list-func" onclick="$('#show-voting').slideToggle('slow')">voting</div>
<div id="show-voting" class="content-func" style="display:none">
این تابع فرم نظرسنجی را نمایش می دهد و تنظیمات اختصاصی دارد.
<div style="text-align:left;direction:ltr;font-weight:bold;margin:4px">
<span style="color:#CC0000">[- options -]</span><br />
<span style="color:#009933">id</span> <span style="color:#3366CC">=</span> <span style="color:#9900CC">0</span><br />
</div>
این تابع به صورت پیشفرض آخرین نظرسنجی را در بلوک نمایش می دهد اما اگر بخواهید یک نظرسنجی قدیمی را نمایش دهید می توانید آی دی آن نظرسنجی را
مشخص کنید تا آن نظرسنجی در بلوک نمایش داده شود.
</div>		

</div>
<!-- /option-id-4 -->

<div class="clear"></div>
</div>
</div>
<div class="content-bottom"></div>
</div>
<!-- TAB END-->
[/not-show-list]