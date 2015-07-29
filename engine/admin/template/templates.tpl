[not-ajax]
<script type="text/javascript">
var themeDefault = '{theme}';
var list_update = false;
function templates_ajax(id)
{
    if(id == 1)
	{
		if (!list_update)
		{
			return false;
		}
		apadana.ajax({
			type: 'GET',
			action: '{admin-page}&section=templates',
            loading: 'no',
			beforeSend: function()
			{
				list_update = false;
				apadana.html('option-id-1', '<p><center><img src="{site-url}engine/images/loading/loader-9.gif" width="54" height="55"></center></p>');
			},
			success: function(data)
			{
        		if(apadana.$('option-id-1').style.display=='block')
				{
					$('#option-id-1').fadeOut('slow', function(){
						$('#option-id-1').html(data).fadeIn('slow')
					})
				}
			}
		})
	}
	else if(id == 2)
	{
		apadana.html('templates-upload-process', 'فایل ارسالی در پوشه templates آپادانا آپلود خواهد شد.<br><br>');
		apadana.$('templates-upload-form').reset();
		apadana.showID('templates-upload-form');
	}
}
function templates_default(name)
{
    if(!confirm('آیا از این کار اطمینان دارید؟')) return;
    apadana.ajax({
        type: 'GET',
        action: '{admin-page}&section=templates&do=default&name='+name,
        json: 'yes',
        success: function(data)
        {
            if(data.result == 'status')
            	alert('تم ناقص است!');
            else if(data.result == 'error')
            	alert('خطایی رخ داده مجدد تلاش کنید');
            else if(data.result == 'success')
			{
    			apadana.changeSrc('templates-default-'+name, 'engine/images/icons/burn.png');
				apadana.attr('templates-default-'+name, 'onmouseover','tooltip.show(\'تم سایت\')');
				
    			apadana.changeSrc('templates-default-'+themeDefault, 'engine/images/icons/brightness-low.png');
				apadana.attr('templates-default-'+themeDefault, 'onmouseover','tooltip.show(\'انتخاب برای تم سایت\')');
				themeDefault = name;
			}
        }
    });
}
function templates_info(name)
{
    apadana.changeSrc('templates-infoBox-screenshot', apadana.value('data-templates-screenshot-'+name));
    apadana.html('templates-infoBox-name', name);
    apadana.html('templates-infoBox-version', apadana.value('data-templates-version-'+name));
    apadana.html('templates-infoBox-creationDate', apadana.value('data-templates-creationDate-'+name));
    apadana.html('templates-infoBox-author', apadana.value('data-templates-author-'+name));
    apadana.html('templates-infoBox-authorEmail', apadana.value('data-templates-authorEmail-'+name));
    apadana.html('templates-infoBox-license', apadana.value('data-templates-license-'+name));
    apadana.html('templates-infoBox-description', apadana.value('data-templates-description-'+name));
    apadana.html('templates-infoBox-positions', apadana.value('data-templates-positions-'+name));
    apadana.html('templates-infoBox-pages', apadana.value('data-templates-pages-'+name));
    apadana.html('templates-infoBox-compaction', apadana.value('data-templates-compaction-'+name));
    apadana.html('templates-infoBox-authorUrl', apadana.value('data-templates-authorUrl-'+name)=='unknown'? 'ناشناخته' : 'مشاهده سایت سازنده');
    apadana.$('templates-infoBox-authorUrl').href = apadana.value('data-templates-authorUrl-'+name)=='unknown'? 'javascript:void(0)' : apadana.value('data-templates-authorUrl-'+name);
	apadana.infoBox('#templates-info-box', 1 , 1);
}
function templates_startUpload()
{
	list_update = true;
	apadana.html('templates-upload-process', '<p><center><img src="{site-url}engine/images/loading/loader-9.gif" width="54" height="55"></center></p>');
	apadana.hideID('templates-upload-form');
}
function templates_stopUpload(result)
{
	list_update = true;
	apadana.html('templates-upload-process', result+'<br><br>');
	apadana.$('templates-upload-form').reset();
	apadana.showID('templates-upload-form');
}
</script>

<div id="templates-info-box" style="display:none">
<center><img src="" id="templates-infoBox-screenshot" width="180" style="margin-top:6px" /></center>
<ul>
  <li>نام تم: <strong id="templates-infoBox-name"></strong></li>
  <li>نگارش تم: <strong id="templates-infoBox-version" dir="ltr"></strong></li>
  <li>زمان ساخت: <strong id="templates-infoBox-creationDate"></strong></li>
  <li>نام سازنده: <strong id="templates-infoBox-author"></strong></li>
  <li>ایمیل سازنده: <strong dir="ltr" id="templates-infoBox-authorEmail"></strong></li>
  <li>وبسایت سازنده: <strong><a href="" target="_blank" onmouseover="tooltip.show('مشاهده صفحه')" onmouseout="tooltip.hide()" id="templates-infoBox-authorUrl"></a></strong></li>
  <li>لیسانس تم: <strong id="templates-infoBox-license"></strong></li>
  <li>موقعیت بلوک ها: <strong id="templates-infoBox-positions"></strong></li>
  <li>سایر صفحات: <strong id="templates-infoBox-pages"></strong></li>
  <li>فشرده سازی صفحات: <strong id="templates-infoBox-compaction"></strong></li>
  <li>توضیحات: <strong id="templates-infoBox-description"></strong></li>
</ul>
</div>

<!-- TAB START-->
<div class="content">
<div class="content-tabs">
<ul>
  <li class="tab-on" id="tab-id-1" onclick="apadana.changeTab(1, 2, function(){templates_ajax(1)})">فهرست تم ها</li>
  <li class="tab-off" id="tab-id-2" onclick="apadana.changeTab(2, 2, function(){templates_ajax(2)})">نصب تم</li>
</ul>
</div>
<div class="content-main">
<div class="content-space">

<div id="option-id-1">
[/not-ajax]
<table class="apadana-table" cellpadding="0" cellspacing="0">
<thead>
  <tr>
	<th align="right">نام تم</th>
	<th width="50">اطلاعات</th>
	<th width="50">وضعیت</th>
	<th width="50">پیشفرض</th>
	<th width="50">ویرایش</th>
  </tr>
</thead>
<tbody>
[for templates]
  <tr class="{odd-even}">
	<td align="right">{name}
	<div style="display:none">
	<input id="data-templates-version-{name}" type="hidden" value="{info-version}" />
	<input id="data-templates-creationDate-{name}" type="hidden" value="{info-creationDate}" />
	<input id="data-templates-author-{name}" type="hidden" value="{info-author}" />
	<input id="data-templates-authorEmail-{name}" type="hidden" value="{info-authorEmail}" />
	<input id="data-templates-authorUrl-{name}" type="hidden" value="{info-authorUrl}" />
	<input id="data-templates-license-{name}" type="hidden" value="{info-license}" />
	<input id="data-templates-screenshot-{name}" type="hidden" value="{info-screenshot}" />
	<input id="data-templates-positions-{name}" type="hidden" value="{info-positions}" />
	<input id="data-templates-pages-{name}" type="hidden" value="{info-pages}" />
	<input id="data-templates-compaction-{name}" type="hidden" value="{info-compaction}" />
	<textarea id="data-templates-description-{name}">{info-description}</textarea>
	</div>
	</td>
	<td><a href="javascript:templates_info('{name}')"><img src="{site-url}engine/images/icons/external.png" width="16" height="16" onmouseover="tooltip.show('اطلاعات تم')" onmouseout="tooltip.hide()"></a></td>
	<td><img src="{site-url}engine/images/icons/[status]tick-button[/status][not-status]exclamation-button[/not-status].png" onmouseover="tooltip.show('[status]این تم سالم است[/status][not-status]این تم ناقص است[/not-status]')" onmouseout="tooltip.hide()"></td>
	<td><a href="javascript:templates_default('{name}')"><img id="templates-default-{name}" src="{site-url}engine/images/icons/[default]burn[/default][not-default][status]brightness-low[/status][not-status]slash-button[/not-status][/not-default].png" onmouseover="tooltip.show('[default]تم سایت[/default][not-default][status]انتخاب برای تم سایت[/status][not-status]این تم ناقص است[/not-status][/not-default]')" onmouseout="tooltip.hide()"></a></td>
	<td><a href="{admin-page}&section=templates&do=edit&name={name}"><img src="{site-url}engine/images/icons/document-edit-icon.png" onmouseover="tooltip.show('ویرایش تم')" onmouseout="tooltip.hide()"></a></td>
  </tr>
[/for templates]
</tbody>
</table>
[not-ajax]
</div>
<!-- /option-id-1 -->
<div id="option-id-2" style="display:none;text-align:center;padding:40px">

<div id="templates-upload-process"></div>
<form id="templates-upload-form" action="{admin-page}&section=templates&do=upload" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="templates_startUpload();" >
	<label>
		انتخاب فایل: <input name="myfile" type="file" size="30" />
	</label>
	<label>
		<input type="submit" value="آپلود فایل Zip تم" />
	</label>
	<iframe id="upload_target" name="upload_target" src="{admin-page}&section=templates&do=upload" style="width:0;height:0;border:0px solid #fff;"></iframe>
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