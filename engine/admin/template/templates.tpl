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
				apadana.attr('templates-default-'+name, 'data-tooltip','tooltip.show(\'تم سایت\')');
				
    			apadana.changeSrc('templates-default-'+themeDefault, 'engine/images/icons/brightness-low.png');
				apadana.attr('templates-default-'+themeDefault, 'data-tooltip','tooltip.show(\'انتخاب برای تم سایت\')');
				themeDefault = name;
			}
        }
    });
}
function templates_info(name)
{
    apadana.ajax({
		method: 'get',
		action: '{admin-page}&section=templates&do=get_info&name='+name,
		json : 'yes' ,
		success: function(data)
		{
			if (data.result == 'success')
			{
				$('#templates-info-box').html(data.info);
				apadana.infoBox('#templates-info-box',1,1);
			}
			else
			{
				alert(data.message);
			}
		}
	});
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
	<td align="right">{show-name}</td>
	<td><a href="javascript:templates_info('{name}')"><img src="{site-url}engine/images/icons/external.png" width="16" height="16" data-tooltip="اطلاعات تم" ></a></td>
	<td><img src="{site-url}engine/images/icons/[status][compatibility]tick-button[/compatibility][not-compatibility]question-button[/not-compatibility][/status][not-status]exclamation-button[/not-status].png" data-tooltip="[status]این تم سالم است[compatibility] و با نسخه آپادانای شما سازگار است[/compatibility][not-compatibility] ولی با نسخه آپادانای شما سازگار نیست[/not-compatibility][/status][not-status]این تم ناقص است[/not-status]" ></td>
	<td><a href="javascript:templates_default('{name}')"><img id="templates-default-{name}" src="{site-url}engine/images/icons/[default]burn[/default][not-default][status]brightness-low[/status][not-status]slash-button[/not-status][/not-default].png" data-tooltip="[default]تم سایت[/default][not-default][status]انتخاب برای تم سایت[/status][not-status]این تم ناقص است[/not-status][/not-default]" ></a></td>
	<td><a href="{admin-page}&section=templates&do=edit&name={name}"><img src="{site-url}engine/images/icons/document-edit-icon.png" data-tooltip="ویرایش تم" ></a></td>
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