[show-list]
[list]
<table class="apadana-table" cellpadding="0" cellspacing="0">
  <tr>
    <th width="5">#</th>
    <th align="right">عنوان پیوند</th>
    <th width="10">نوع</th>
    <th width="10">مشاهده</th>
    <th width="10">وضعیت</th>
    <th width="30">عملیات</th>
  </tr>
[for list]
  <tr class="{odd-even}" id="sLink-{id}">
    <td>{id}
	<div style="display:none">
	<input id="data-sLink-title-{id}" type="hidden" value="{title}" />
	<input id="data-sLink-href-{id}" type="hidden" value="{href}" />
	<input id="data-sLink-target-{id}" type="hidden" value="{target}" />
	<input id="data-sLink-direct-{id}" type="hidden" value="{direct-link}" />
	<input id="data-sLink-color-{id}" type="hidden" value="{color}" />
	<input id="data-sLink-bold-{id}" type="hidden" value="{bold}" />
	<input id="data-sLink-strikethrough-{id}" type="hidden" value="{strikethrough}" />
	<input id="data-sLink-active-{id}" type="hidden" value="{active}" />
	<textarea id="data-sLink-description-{id}">{description}</textarea>
	</div>
	</td>
    <td align="right">{title}</td>
    <td><img src="{site-url}engine/images/icons/[direct-link]fire[/direct-link][not-direct-link]water[/not-direct-link].png" width="16" height="16" onmouseover="tooltip.show('[direct-link]پیوند به صورت مستقیم[/direct-link][not-direct-link]پیوند به صورت غیرمستقیم[/not-direct-link]')" onmouseout="tooltip.hide()"></td>
    <td><a href="{redirect}" target="_blank"><img src="{site-url}engine/images/icons/eye.png" width="16" height="16" onmouseover="tooltip.show('مشاهده پیوند')" onmouseout="tooltip.hide()"></a></td>
    <td><a href="javascript:sLink_active({id})"><img src="{site-url}engine/images/icons/[active]plus-button[/active][not-active]minus-button[/not-active].png" width="16" height="16" onmouseover="tooltip.show('[active]فعال[/active][not-active]غیرفعال[/not-active]')" onmouseout="tooltip.hide()" id="sLink-active-{id}"></a></td>
    <td><a href="javascript:sLink_edit({id})"><img src="{site-url}engine/images/icons/document-edit-icon.png" width="16" height="16" onmouseover="tooltip.show('ویرایش')" onmouseout="tooltip.hide()"></a> <a href="javascript:sLink_delete({id})"><img src="{site-url}engine/images/icons/cross-script.png" width="16" height="16" onmouseover="tooltip.show('حذف')" onmouseout="tooltip.hide()"></a></td>
  </tr>
[/for list]
</table>
[/list]
[not-list]
<center>{function name="message" args="هیچ پیوند وجود ندارد!|info"}</center>
[/not-list]
[/show-list]
[not-show-list]
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function sLink_ajax(id)
{
    if (id == 1)
	{
		apadana.showID('form-new-sLink');
		apadana.html('option-ajax-1', '');
		apadana.$('form-new-sLink').reset();
	}
	else if (id == 2)
	{
		apadana.ajax({
            method: 'get',
            action: '{admin-page}&module=simple-links&do=list',
            loading: 'no',
            beforeSend: function()
            {
				apadana.html('option-id-2', '<p><center><img src="{site-url}engine/images/loading/loader-9.gif" width="54" height="55"></center></p>');
            },
            success: function(data)
            {
        		if (!apadana.browser.ie)
        		apadana.fadeOut('option-id-2', function(){
					apadana.html('option-id-2', data);
					apadana.fadeIn('option-id-2');
				});
				else
				apadana.html('option-id-2', data);
           }
		})
	}
	else if (id == 3)
	{
        apadana.changeTab(2, 3, function(){sLink_ajax(2)})
        alert('ابتدا یک پیوند را برای ویرایش انتخاب کنید!');
	}
}
function sLink_new()
{
	apadana.html('option-ajax-1', '');
	apadana.ajax({
		method: 'post',
		action: '{admin-page}&module=simple-links&do=new',
		data: apadana.serialize('form-new-sLink'),
		success: function(data)
		{
			apadana.html('option-ajax-1', data);
		}
	})
}
function sLink_edit(ID)
{
	apadana.html('option-ajax-3', '');
    if (ID=='save')
    {
		ID = apadana.value('link-id-edit');
		apadana.ajax({
            method: 'post',
            action: '{admin-page}&module=simple-links&do=edit&id='+ID,
            data: apadana.serialize('form-edit-sLink'),
            success: function(data)
            {
			    apadana.html('option-ajax-3', data);
            }
		})
    }
    else
    {
		apadana.value('link-id-edit', ID);
        apadana.value('link-title-edit', apadana.value('data-sLink-title-'+ID));
        apadana.value('link-href-edit', apadana.value('data-sLink-href-'+ID));
        apadana.value('link-description-edit', apadana.value('data-sLink-description-'+ID));
        apadana.value('link-color-edit', apadana.value('data-sLink-color-'+ID));

		if (apadana.value('data-sLink-color-'+ID)=='')
		{
			apadana.$('link-no-color-edit').checked='checked';
		}
		else
		{
			apadana.$('link-no-color-edit').checked=false;
		}
		
		if (apadana.value('data-sLink-target-'+ID)=='_blank')
		{
			apadana.$('link-target-edit').checked='checked';
		}
		else
		{
			apadana.$('link-target-edit').checked=false;
		}
		
		if (apadana.value('data-sLink-direct-'+ID)==1)
		{
			apadana.$('link-direct-edit').checked='checked';
		}
		else
		{
			apadana.$('link-direct-edit').checked=false;
		}
		
		if (apadana.value('data-sLink-bold-'+ID)==1)
		{
			apadana.$('link-bold-edit').checked='checked';
		}
		else
		{
			apadana.$('link-bold-edit').checked=false;
		}
		
		if (apadana.value('data-sLink-strikethrough-'+ID)==1)
		{
			apadana.$('link-strikethrough-edit').checked='checked';
		}
		else
		{
			apadana.$('link-strikethrough-edit').checked=false;
		}
		
		if (apadana.value('data-sLink-active-'+ID)==1)
		{
			apadana.$('link-active-edit').checked='checked';
		}
		else
		{
			apadana.$('link-active-edit').checked=false;
		}
		
        apadana.changeTab(3, 3)
    }
}
function sLink_active(ID)
{
    apadana.ajax({
        method: 'get',
		action: '{admin-page}&module=simple-links&do=active',
        data: 'id='+ID,
        success: function(active)
        {
			active = apadana.trim(active);
			if (active == 'active')
			{
				apadana.changeSrc('sLink-active-'+ID, '{site-url}engine/images/icons/plus-button.png');
				apadana.attr('sLink-active-'+ID, 'onmouseover','tooltip.show(\'فعال\')');
				apadana.value('data-sLink-active-'+ID, 1);
			}
			else if (active == 'inactive')
			{
				apadana.changeSrc('sLink-active-'+ID, '{site-url}engine/images/icons/minus-button.png');
				apadana.attr('sLink-active-'+ID, 'onmouseover','tooltip.show(\'غیرفعال\')');
				apadana.value('data-sLink-active-'+ID, 0);
			}
			else
				alert(active);
	    }
    })
}
function sLink_delete(ID)
{
    if (confirm('آیا از حذف این پیوند اطمینان دارید؟'))
    apadana.ajax({
        method: 'get',
 		action: '{admin-page}&module=simple-links&do=delete',
        data: 'id='+ID,
        id: 'option-id-2',
    })
}
/*]]>*/
</script>

<!-- TAB START-->
<div class="content">
<div class="content-tabs">
<ul>
  <li class="tab-on" id="tab-id-1" onclick="apadana.changeTab(1, 3, function(){sLink_ajax(1)})">پیوند جدید</li>
  <li class="tab-off" id="tab-id-2" onclick="apadana.changeTab(2, 3, function(){sLink_ajax(2)})">لیست پیوندها</li>
  <li class="tab-off" id="tab-id-3" onclick="apadana.changeTab(3, 3, function(){sLink_ajax(3)})">ویرایش پیوند</li>
</ul>
</div>
<div class="content-main">
<div class="content-space">

<div id="option-id-1" style="display:block">
<div id="option-ajax-1"></div>
<form id="form-new-sLink" onsubmit="sLink_new();return false">
<table cellpadding="6" cellspacing="0">
  <tr>
	<td width="90">عنوان</td>
	<td><input name="link[title]" type="text" size="60" lang="fa-ir" /></td>
  </tr>
  <tr>
	<td>آدرس</td>
	<td><input name="link[href]" type="text" size="60" value="http://" dir="ltr" /></td>
  </tr>
  <tr>
	<td>توضیحات</td>
	<td><textarea name="link[description]" cols="75" rows="6" lang="fa-ir"></textarea></td>
  </tr>
  <tr>
	<td>رنگ پیوند</td>
	<td><input name="link[color]" type="text" style="width:45px;text-align:center" value="#0080c0" class="color" dir="ltr" />&nbsp;&nbsp;<label><input name="link[no-color]" type="checkbox" value="1" />&nbsp;برای این پیوند از رنگ استفاده نکن!</label></td>
  </tr>
  <tr>
	<td>هدف</td>
	<td><label><input name="link[target]" type="checkbox" value="1" checked="checked" />&nbsp;پیوند در صفحه جدید باز شود.</label></td>
  </tr>
  <tr>
	<td>پیوند مستقیم</td>
	<td><label><input name="link[direct-link]" type="checkbox" value="1" checked="checked" />&nbsp;آدرس پیوند به صورت ساده و مستقیم باشد.</label></td>
  </tr>
  <tr>
	<td>پیوند توپر</td>
	<td><label><input name="link[bold]" type="checkbox" value="1" />&nbsp;متن پیوند به صورت توپر باشد.</label></td>
  </tr>
  <tr>
	<td>پیوند خط خورده</td>
	<td><label><input name="link[strikethrough]" type="checkbox" value="1" />&nbsp;متن پیوند به صورت خط خورده باشد.</label></td>
  </tr>
  <tr>
	<td>وضعیت</td>
	<td><label><input name="link[active]" type="checkbox" value="1" checked="checked" />&nbsp;پیوند فعال باشد.</label></td>
  </tr>
  <tr>
	<td></td>
	<td><input type="submit" value="ذخیره پیوند"> &nbsp; <input type="reset" value="از نو" onclick="sLink_ajax(1)"></td>
  </tr>
</table>
</form>
</div>
<!-- /option-id-1 -->
<div id="option-id-2" style="display:none"></div>
<!-- /option-id-2 -->
<div id="option-id-3" style="display:none">
<div id="option-ajax-3"></div>
<form id="form-edit-sLink" onsubmit="sLink_edit('save');return false">
<table cellpadding="6" cellspacing="0">
  <tr>
	<td width="90">عنوان</td>
	<td><input name="link[title]" id="link-title-edit" type="text" size="60" lang="fa-ir" /></td>
  </tr>
  <tr>
	<td>آدرس</td>
	<td><input name="link[href]" id="link-href-edit" type="text" size="60" value="http://" dir="ltr" /></td>
  </tr>
  <tr>
	<td>توضیحات</td>
	<td><textarea name="link[description]" id="link-description-edit" cols="75" rows="6" lang="fa-ir"></textarea></td>
  </tr>
  <tr>
	<td>رنگ پیوند</td>
	<td><input name="link[color]" type="text" style="width:45px;text-align:center" value="#0080c0" class="color" dir="ltr" id="link-color-edit" />&nbsp;&nbsp;<label><input name="link[no-color]" type="checkbox" value="1" id="link-no-color-edit" />&nbsp;برای این پیوند از رنگ استفاده نکن!</label></td>
  </tr>
  <tr>
	<td>هدف</td>
	<td><label><input name="link[target]" id="link-target-edit" type="checkbox" value="1" checked="checked" />&nbsp;پیوند در صفحه جدید باز شود.</label></td>
  </tr>
  <tr>
	<td>پیوند مستقیم</td>
	<td><label><input name="link[direct-link]" id="link-direct-edit" type="checkbox" value="1" checked="checked" />&nbsp;آدرس پیوند به صورت ساده و مستقیم باشد.</label></td>
  </tr>
  <tr>
	<td>پیوند توپر</td>
	<td><label><input name="link[bold]" id="link-bold-edit" type="checkbox" value="1" />&nbsp;متن پیوند به صورت توپر باشد.</label></td>
  </tr>
  <tr>
	<td>پیوند خط خورده</td>
	<td><label><input name="link[strikethrough]" id="link-strikethrough-edit" type="checkbox" value="1" />&nbsp;متن پیوند به صورت خط خورده باشد.</label></td>
  </tr>
  <tr>
	<td>وضعیت</td>
	<td><label><input name="link[active]" id="link-active-edit" type="checkbox" value="1" checked="checked" />&nbsp;پیوند فعال باشد.</label></td>
  </tr>
  <tr>
	<td><input id="link-id-edit" type="hidden" value="0" /></td>
	<td><input type="submit" value="ویرایش پیوند"></td>
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
[/not-show-list]