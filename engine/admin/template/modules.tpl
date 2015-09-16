[not-ajax]
<script type="text/javascript">
var moduleDefault = '{default-module}';
var list_update = false;
function modules_ajax(id)
{
    if (id == 1)
	{
		if (!list_update)
		{
			return false;
		}
		apadana.ajax({
			type: 'GET',
			action: '{admin-page}&section=modules',
            loading: 'no',
			beforeSend: function()
			{
				apadana.html('option-id-1', '<p><center><img src="{site-url}engine/images/loading/loader-9.gif" width="54" height="55"></center></p>');
			},
			success: function(data)
			{
				list_update = false;
       			if (apadana.$('option-id-1').style.display=='block')
				{
					if (!apadana.browser.ie)
					apadana.fadeOut('option-id-1', function(){
						apadana.html('option-id-1', data);
						apadana.fadeIn('option-id-1');
					});
					else
					apadana.html('option-id-1', data);
				}
			}
		})
	}
	else if (id == 2)
	{
		apadana.html('modules-upload-process', 'فایل ارسالی در پوشه modules آپادانا آپلود خواهد شد.<br><br>');
		apadana.$('modules-upload-form').reset();
		apadana.showID('modules-upload-form');
	}
}
function modules_default(name)
{
    if (!confirm("آیا از انتخاب این ماژول برای پیشفرض اطمینان دارید؟\n"+name)) return;
    apadana.ajax({
        type: 'GET',
        action: '{admin-page}&section=modules&do=default&name='+name,
        json: 'yes',
        success: function(data)
        {
            if (data.result == 'inactive')
            	alert('این ماژول غیرفعال است!');
            else if (data.result == 'error')
            	alert('خطایی رخ داده مجدد تلاش کنید');
            else if (data.result == 'success')
			{
    			apadana.changeSrc('modules-default-'+name, '{site-url}engine/images/icons/burn.png');
				apadana.attr('modules-default-'+name, 'onmouseover','tooltip.show(\'ماژول فعال در صفحه نخست سایت\')');
				
    			apadana.changeSrc('modules-default-'+moduleDefault, '{site-url}engine/images/icons/brightness-low.png');
				apadana.attr('modules-default-'+moduleDefault, 'onmouseover','tooltip.show(\'انتخاب برای فعال بودن در صفحه نخست\')');
				moduleDefault = name;
			}
        }
    });
}
function modules_active(name)
{
    if (!confirm("آیا از فعال/غیرفعال سازی این ماژول اطمینان دارید؟\n"+name)) return;
    apadana.ajax({
        type: 'GET',
        action: '{admin-page}&section=modules&do=active&name='+name,
        id: 'option-id-1',
    });
}
function modules_install(name)
{
    if (!confirm("آیا از نصب/حذف این ماژول اطمینان دارید؟\n"+name)) return;
    apadana.ajax({
        type: 'GET',
        action: '{admin-page}&section=modules&do=install&name='+name,
        id: 'option-id-1',
    });
}
function modules_upgrade(name)
{
    if (!confirm("آیا از بروزرسانی این ماژول اطمینان دارید؟\nممکن است کمی طول بکشد.\n"+name)) return;
    apadana.ajax({
        type: 'GET',
        action: '{admin-page}&section=modules&do=upgrade&name='+name,
        id: 'option-id-1',
    });
}
function modules_info(name)
{
    apadana.ajax({
		method: 'get',
		action: '{admin-page}&section=modules&do=get_info&name='+name,
		json : 'yes' ,
		success: function(data)
		{
			if (data.result == 'success')
			{
				$('#infobox').html(data.info);
				apadana.infoBox('#infobox',1,1);
			}
			else
			{
				alert(data.message);
			}
		}
	});
}
function modules_startUpload()
{
	list_update = true;
	apadana.html('modules-upload-process', '<p><center><img src="{site-url}engine/images/loading/loader-9.gif" width="54" height="55"></center></p>');
	apadana.hideID('modules-upload-form');
}
function modules_stopUpload(result)
{
	list_update = true;
	apadana.html('modules-upload-process', result+'<br><br>');
	apadana.$('modules-upload-form').reset();
	apadana.showID('modules-upload-form');
}
</script>

<div id="infobox" style="display:none">
</div>

<!-- TAB START-->
<div class="content">
<div class="content-tabs">
<ul>
  <li class="tab-on" id="tab-id-1" onclick="apadana.changeTab(1, 2, function(){modules_ajax(1)})">فهرست ماژول ها</li>
  <li class="tab-off" id="tab-id-2" onclick="apadana.changeTab(2, 2, function(){modules_ajax(2)})">نصب ماژول</li>
</ul>
</div>
<div class="content-main">
<div class="content-space">

<div id="option-id-1" style="display:block">
[/not-ajax]
<table class="apadana-table" cellpadding="0" cellspacing="0">
<thead>
  <tr>
	<th align="right">نام ماژول</th>
	<th data-tooltip="سلام" width="50">اطلاعات</th>
	<th width="50">وضعیت</th>
	<th width="50">پیشفرض</th>
	<th width="50">فعال</th>
	<th width="50">نصب</th>
	<th width="50">بروزرسانی</th>
  </tr>
</thead>
<tbody>
[for modules]
  <tr class="{odd-even}">
	<td dir="rtl" align="right">{show-name}</td>
	<td><a href="javascript:modules_info('{name}')"><img src="{site-url}engine/images/icons/external.png" width="16" height="16" data-tooltip="اطلاعات ماژول" ></a></td>
	<td><img src="{site-url}engine/images/icons/[status][compatibility]tick-button[/compatibility][not-compatibility]question-button[/not-compatibility][/status][not-status]exclamation-button[/not-status].png" data-tooltip="[status]این ماژول سالم است[compatibility] و با نسخه آپادانای شما سازگار است[/compatibility][not-compatibility] ولی با نسخه آپادانای شما سازگار نیست[/not-compatibility][/status][not-status]این ماژول ناقص است[/not-status]" ></td>
	<td><a href="javascript:modules_default('{name}')"><img id="modules-default-{name}" src="{site-url}engine/images/icons/[default]burn[/default][not-default][install]brightness-low[/install][not-install]puzzle--exclamation[/not-install][/not-default].png" data-tooltip="[default]ماژول فعال در صفحه نخست سایت[/default][not-default][status][install]انتخاب برای فعال بودن در صفحه نخست[/install][not-install]ماژول نصب نشده است[/not-install][/status][not-status]این ماژول ناقص است[/not-status][/not-default]" ></a></td>
	<td><a href="javascript:modules_active('{name}')"><img src="{site-url}engine/images/icons/[active]flag-green[/active][not-active]flag-pink[/not-active].png" data-tooltip="[active]غیرفعال کردن[/active][not-active]فعال کردن[/not-active]" ></a></td>
	<td><a href="javascript:modules_install('{name}')"><img src="{site-url}engine/images/icons/[status][install]puzzle--minus[/install][not-install]plus-button[/not-install][/status][not-status]exclamation-button[/not-status].png" data-tooltip="[status][install]حذف کردن[/install][not-install]نصب کردن[/not-install][/status][not-status]این ماژول ناقص است[/not-status]" ></a></td>
	<td><a href="javascript:modules_upgrade('{name}')"><img src="{site-url}engine/images/icons/[status][upgrade]puzzle--arrow[/upgrade][not-upgrade]slash-button[/not-upgrade][/status][not-status]exclamation-button[/not-status].png" data-tooltip="[status][upgrade]دارای بروزرسانی[/upgrade][not-upgrade]نیازی به بروزرسانی ندارد[/not-upgrade][/status][not-status]این ماژول ناقص است[/not-status]" ></a></td>
  </tr>
[/for modules]
</tbody>
</table>
[not-ajax]
</div>
<!-- /option-id-1 -->
<div id="option-id-2" style="display:none;text-align:center;padding:40px">

<div id="modules-upload-process"></div>
<form id="modules-upload-form" action="{admin-page}&section=modules&do=upload" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="modules_startUpload();" >
	<label>
		انتخاب فایل: <input name="myfile" type="file" size="30" />
	</label>
	<label>
		<input type="submit" value="آپلود فایل Zip ماژول" />
	</label>
	<iframe id="upload_target" name="upload_target" src="{admin-page}&section=modules&do=upload" style="width:0;height:0;border:0px solid #fff;"></iframe>
 </form>

</div>
<!-- /option-id-2 -->

<div class="clear"></div>
</div>
</div>
<div class="content-bottom"></div>
</div>
<!-- TAB END-->
[/not-ajax]