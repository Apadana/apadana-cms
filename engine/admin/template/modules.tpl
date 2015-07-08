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
    if (!confirm("آیا از نصب این ماژول اطمینان دارید؟\n"+name)) return;
    apadana.ajax({
        type: 'GET',
        action: '{admin-page}&section=modules&do=install&name='+name,
        id: 'option-id-1',
    });
}
function modules_uninstall(name)
{
    if (!confirm("آیا از حذف این ماژول اطمینان دارید؟\nکل اطلاعات ماژول حذف خواهد شد!\n"+name)) return;
    apadana.ajax({
        type: 'GET',
        action: '{admin-page}&section=modules&do=uninstall&name='+name,
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
    apadana.html('modules-infoBox-name', name);
    apadana.html('modules-infoBox-version', apadana.value('data-modules-version-'+name));
    apadana.html('modules-infoBox-creationDate', apadana.value('data-modules-creationDate-'+name));
    apadana.html('modules-infoBox-author', apadana.value('data-modules-author-'+name));
    apadana.html('modules-infoBox-authorEmail', apadana.value('data-modules-authorEmail-'+name)=='unknown'? 'ناشناخته' : apadana.value('data-modules-authorEmail-'+name));
    apadana.html('modules-infoBox-license', apadana.value('data-modules-license-'+name));
    apadana.html('modules-infoBox-description', apadana.value('data-modules-description-'+name));

    apadana.html('modules-infoBox-authorUrl', apadana.value('data-modules-authorUrl-'+name)=='unknown'? 'ناشناخته' : 'مشاهده سایت سازنده');
    apadana.$('modules-infoBox-authorUrl').href = apadana.value('data-modules-authorUrl-'+name)=='unknown'? 'javascript:alert(\'آدرسی برای این ماژول در دسترس نمی باشد!\');return false' : apadana.value('data-modules-authorUrl-'+name);

	apadana.infoBox('#module-info-box', 1,1);
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

<div id="module-info-box" style="display:none">
<ul>
  <li>نام ماژول: <strong id="modules-infoBox-name"></strong></li>
  <li>نگارش ماژول: <strong id="modules-infoBox-version" dir="ltr"></strong></li>
  <li>زمان ساخت: <strong id="modules-infoBox-creationDate"></strong></li>
  <li>نام سازنده: <strong id="modules-infoBox-author"></strong></li>
  <li>ایمیل سازنده: <strong dir="ltr" id="modules-infoBox-authorEmail"></strong></li>
  <li>وبسایت سازنده: <strong><a href="" target="_blank" onmouseover="tooltip.show('مشاهده صفحه')" onmouseout="tooltip.hide()" id="modules-infoBox-authorUrl"></a></strong></li>
  <li>لیسانس ماژول: <strong id="modules-infoBox-license"></strong></li>
  <li>توضیحات: <strong id="modules-infoBox-description"></strong></li>
</ul>
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
	<th width="50">اطلاعات</th>
	<th width="50">وضعیت</th>
	<th width="50">پیشفرض</th>
	<th width="50">فعال</th>
	<th width="50">نصب</th>
	<th width="50">حذف</th>
	<th width="50">بروزرسانی</th>
  </tr>
</thead>
<tbody>
[for modules]
  <tr class="{odd-even}">
	<td align="right">{name}
	<div style="display:none">
	<input id="data-modules-version-{name}" type="hidden" value="{info-version}" />
	<input id="data-modules-creationDate-{name}" type="hidden" value="{info-creationDate}" />
	<input id="data-modules-author-{name}" type="hidden" value="{info-author}" />
	<input id="data-modules-authorEmail-{name}" type="hidden" value="{info-authorEmail}" />
	<input id="data-modules-authorUrl-{name}" type="hidden" value="{info-authorUrl}" />
	<input id="data-modules-license-{name}" type="hidden" value="{info-license}" />
	<textarea id="data-modules-description-{name}">{info-description}</textarea>
	</div>
	</td>
	<td><a href="javascript:modules_info('{name}')"><img src="{site-url}engine/images/icons/external.png" width="16" height="16" onmouseover="tooltip.show('اطلاعات ماژول')" onmouseout="tooltip.hide()"></a></td>
	<td><img src="{site-url}engine/images/icons/[status]tick-button[/status][not-status]exclamation-button[/not-status].png" onmouseover="tooltip.show('[status]این ماژول سالم است[/status][not-status]این ماژول ناقص است[/not-status]')" onmouseout="tooltip.hide()"></td>
	<td><a href="javascript:modules_default('{name}')"><img id="modules-default-{name}" src="{site-url}engine/images/icons/[default]burn[/default][not-default][install]brightness-low[/install][not-install]puzzle--exclamation[/not-install][/not-default].png" onmouseover="tooltip.show('[default]ماژول فعال در صفحه نخست سایت[/default][not-default][status][install]انتخاب برای فعال بودن در صفحه نخست[/install][not-install]ماژول نصب نشده است[/not-install][/status][not-status]این ماژول ناقص است[/not-status][/not-default]')" onmouseout="tooltip.hide()"></a></td>
	<td><a href="javascript:modules_active('{name}')"><img src="{site-url}engine/images/icons/[active]flag-green[/active][not-active]flag-pink[/not-active].png" onmouseover="tooltip.show('[active]فعال[/active][not-active]غیرفعال[/not-active]')" onmouseout="tooltip.hide()"></a></td>
	<td><a href="javascript:modules_install('{name}')"><img src="{site-url}engine/images/icons/[status][install]puzzle[/install][not-install]plus-button[/not-install][/status][not-status]exclamation-button[/not-status].png" onmouseover="tooltip.show('[status][install]نصب شده[/install][not-install]نصب نشده[/not-install][/status][not-status]این ماژول ناقص است[/not-status]')" onmouseout="tooltip.hide()"></a></td>
	<td><a href="javascript:modules_uninstall('{name}')"><img src="{site-url}engine/images/icons/[uninstall]puzzle--minus[/uninstall][not-uninstall]exclamation-button[/not-uninstall].png" onmouseover="tooltip.show('[uninstall]حذف[/uninstall][not-uninstall]نصب نشده[/not-uninstall]')" onmouseout="tooltip.hide()"></a></td>
	<td><a href="javascript:modules_upgrade('{name}')"><img src="{site-url}engine/images/icons/[status][upgrade]puzzle--arrow[/upgrade][not-upgrade]slash-button[/not-upgrade][/status][not-status]exclamation-button[/not-status].png" onmouseover="tooltip.show('[status][upgrade]دارای بروزرسانی[/upgrade][not-upgrade]نیازی به بروزرسانی ندارد[/not-upgrade][/status][not-status]این ماژول ناقص است[/not-status]')" onmouseout="tooltip.hide()"></a></td>
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