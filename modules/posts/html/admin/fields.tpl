[not-ajax]
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function fields_ajax(id)
{
    if (id == 1)
	{
		apadana.$('new-fields-form').reset();
		apadana.showID('new-fields-form');
		apadana.html('option-ajax-1', '');
	}
	else if (id == 2)
	{
		apadana.ajax({
            method: 'get',
            action: '{admin-page}&module=posts&do=fields',
            loading: 'no',
            beforeSend: function()
            {
				apadana.html('option-id-2', '<p><center><img src="{site-url}engine/images/loading/loader-9.gif" width="54" height="55"></center></p>');
            },
            success: function(data)
            {
				apadana.html('option-id-2', data);
			}
		})
	}
	else if (id == 3)
	{
        apadana.changeTab(2, 4, function(){fields_ajax(2)})
        alert('ابتدا یک فیلد را برای ویرایش انتخاب کنید!');
	}
}
function fields_new()
{
    apadana.html('option-ajax-1', '');
	apadana.ajax({
		method: 'post',
		action: '{admin-page}&module=posts&do=fields-new',
		data: apadana.serialize('new-fields-form'),
		success: function(data)
		{
			apadana.fadeOut('option-ajax-1', function(){
				apadana.html('option-ajax-1', data);
				apadana.fadeIn('option-ajax-1');
			});
		}
	})
}
function fields_edit(name)
{
    apadana.html('option-ajax-3', '');
    if (name == '@save')
    {
		name = apadana.value('edit-fields-name-old');
		apadana.ajax({
            method: 'post',
			action: '{admin-page}&module=posts&do=fields-edit&name='+name,
            data: apadana.serialize('edit-fields-form'),
            success: function(data)
            {
        		apadana.fadeOut('option-ajax-3', function(){
					apadana.html('option-ajax-3', data);
					apadana.fadeIn('option-ajax-3');
				});
            }
		})
    }
    else
    {
        apadana.changeTab(3, 4);
		apadana.value('edit-fields-name-old', name);
		apadana.value('edit-fields-name', name);
		apadana.value('edit-fields-title', apadana.value('data-fields-title-'+name));
		apadana.value('edit-fields-default', apadana.value('data-fields-default-'+name));
		
		for (var i = 0 ; i < apadana.$('edit-fields-type').options.length; i++)
		{
			if (apadana.$('edit-fields-type').options[i].value == apadana.value('data-fields-type-'+name))
			{
				apadana.$('edit-fields-type').options[i].selected = 'selected';
				break;
			}
		}

		if (apadana.value('data-fields-require-'+name) == 1)
		{
			apadana.$('edit-fields-require-1').checked = 'checked';
			apadana.$('edit-fields-require-0').checked = false;
		}
		else
		{
			apadana.$('edit-fields-require-1').checked = false;
			apadana.$('edit-fields-require-0').checked = 'checked';
		}
    }
}
function fields_delete(name)
{
    if (confirm("آیا از حذف این فیلد اطمینان دارید؟\nکل اطلاعات این فیلد حذف خواهد شد!\n"+name))
    apadana.ajax({
        method: 'get',
		action: '{admin-page}&module=posts&do=fields-delete',
        data: 'name='+name,
		id: 'option-id-2'
    })
}
/*]]>*/
</script>

<!-- TAB START-->
<div class="content">
<div class="content-tabs">
<ul>
  <li class="tab-on" id="tab-id-1" onclick="apadana.changeTab(1, 4, function(){fields_ajax(1)})">فیلد جدید</li>
  <li class="tab-off" id="tab-id-2" onclick="apadana.changeTab(2, 4, function(){fields_ajax(2)})">فهرست فیلدهای اضافی</li>
  <li class="tab-off" id="tab-id-3" onclick="apadana.changeTab(3, 4, function(){fields_ajax(3)})">ویرایش فیلد</li>
  <li class="tab-off" id="tab-id-4" onclick="apadana.changeTab(4, 4)">راهنما</li>
</ul>
</div>
<div class="content-main">
<div class="content-space">

<div id="option-id-1" style="display:block">
<div id="option-ajax-1"></div>
<form id="new-fields-form">
<table cellspacing="7">
  <tr>
	<td width="100">نام فیلد</td>
	<td><input name="fields[name]" value="" type="text" dir="ltr" style="width:200px" /></td>
  </tr>
  <tr>
	<td>عنوان فیلد</td>
	<td><input name="fields[title]" type="text" value="" lang="fa-IR" style="width:200px" /></td>
  </tr>
  <tr>
	<td>نوع فیلد</td>
	<td><select name="fields[type]" size="1"><option value="text" selected="selected">فیلد کوچک (یک خطی)</option><option value="textarea">جعبه متن ساده</option><option value="editor">جعبه متن دارای ادیتور</option><option value="select">فهرست</option></select></td>
  </tr>
  <tr>
	<td>مقدار پیشفرض</td>
	<td><textarea name="fields[default]" style="width:70%;height:100px" lang="fa-IR"></textarea></td>
  </tr>
  <tr>
	<td></td>
	<td><font size="1" color="#888888">در صورتی که نوع فیلد را فهرست انتخاب کرده اید در فیلد مقدار پیشفرض، در هر خط یک گزینه را برای فهرست مشخص کنید.</font></textarea></td>
  </tr>
  <tr>
	<td>اجباری فیلد</td>
	<td><label><input type="radio" name="fields[require]" value="1" checked="checked" />بله، وارد کردن مقدار این فیلد اجباری باشد</label>&nbsp;<label><input type="radio" name="fields[require]" value="0" />خیر</label></td>
  </tr>
  <tr>
	<td colspan="2"><input type="button" value="ساختن فیلد" onClick="fields_new()" />&nbsp;<input type="reset" value="پاک کردن فرم" /></td>
  </tr>
</table>
</form>
</div>
<!-- /option-id-1 -->
<div id="option-id-2" style="display:none">
[/not-ajax]
[fields]
<table class="apadana-table" cellpadding="0" cellspacing="0">
<thead>
  <tr>
	<th align="right">نام فیلد</th>
	<th style="width:400px" align="right">عنوان فیلد</th>
	<th style="width:70px">نوع</th>
	<th style="width:60px">وضعیت</th>
	<th style="width:40px">عملیات</th>
  </tr>
</thead>
<tbody>
[for fields]
  <tr class="{odd-even}">
	<td align="right">{name}
	<div style="display:none">
	<input id="data-fields-title-{name}" type="hidden" value="{title}" />
	<input id="data-fields-type-{name}" type="hidden" value="{type}" />
	<input id="data-fields-require-{name}" type="hidden" value="{require}" />
	<textarea id="data-fields-default-{name}">{default}</textarea>
	</div>
	</td>
	<td align="right">{title}</td>
	<td>[text]فیلد کوچک[/text][textarea]جعبه متن[/textarea][editor]ادیتور[/editor][select]فهرست[/select]</td>
	<td><img src="{site-url}engine/images/icons/[require]status-busy[/require][not-require]status[/not-require].png" width="16" height="16" data-tooltip="[require]فیلد اجباری[/require][not-require]فیلد غیر اجباری[/not-require]" ></td>
	<td><a href="javascript:fields_edit('{name}')"><img src="{site-url}engine/images/icons/document-edit-icon.png" width="16" height="16" data-tooltip="ویرایش" ></a>&nbsp;<a href="javascript:fields_delete('{name}')"><img src="{site-url}engine/images/icons/cross-script.png" width="16" height="16" data-tooltip="حذف" ></a></td>
  </tr>
[/for fields]
</tbody>
</table>
[/fields]
[not-fields]{function name="message" args="هیچ فیلد اضافه ای ساخته نشده است!|info"}[/not-fields]
[not-ajax]
</div>
<!-- /option-id-2 -->
<div id="option-id-3" style="display:none">
<div id="option-ajax-3"></div>
<form id="edit-fields-form">
<table cellspacing="7">
  <tr>
	<td width="100">نام فیلد</td>
	<td><input id="edit-fields-name" name="fields[name]" value="" type="text" dir="ltr" style="width:200px" /></td>
  </tr>
  <tr>
	<td>عنوان فیلد</td>
	<td><input id="edit-fields-title" name="fields[title]" type="text" value="" lang="fa-IR" style="width:200px" /></td>
  </tr>
  <tr>
	<td>نوع فیلد</td>
	<td><select id="edit-fields-type" name="fields[type]" size="1"><option value="text">فیلد کوچک (یک خطی)</option><option value="textarea">جعبه متن ساده</option><option value="editor">جعبه متن دارای ادیتور</option><option value="select">فهرست</option></select></td>
  </tr>
  <tr>
	<td>مقدار پیشفرض</td>
	<td><textarea id="edit-fields-default" name="fields[default]" style="width:70%;height:100px" lang="fa-IR"></textarea></td>
  </tr>
  <tr>
	<td></td>
	<td><font size="1" color="#888888">در صورتی که نوع فیلد را فهرست انتخاب کرده اید در فیلد مقدار پیشفرض، در هر خط یک گزینه را برای فهرست مشخص کنید.</font></textarea></td>
  </tr>
  <tr>
	<td>اجباری فیلد</td>
	<td><label><input id="edit-fields-require-1" type="radio" name="fields[require]" value="1" checked="checked" />بله، وارد کردن مقدار این فیلد اجباری باشد</label>&nbsp;<label><input id="edit-fields-require-0" type="radio" name="fields[require]" value="0" />خیر</label></td>
  </tr>
  <tr>
	<td colspan="2"><input id="edit-fields-name-old" type="hidden" value="" /><input type="button" value="ویرایش فیلد" onClick="fields_edit('@save')" /></td>
  </tr>
</table>
</form>
</div>
<!-- /option-id-3 -->
<div id="option-id-4" style="display:none">
با استفاده از این بخش در قسمت ارسال پست می توانید فیلدهایی را اضافه کنید و اطلاعات مورد نظر خود را در آن ها درج کنید و بعد در پست های سایت خود، آن ها را نمایش دهید<br>
بعد از اضافه کردن فیلد می توانید اطلاعاتی که در صفحه ارسال و یا ویرایش پست برای آن درج می کنید را با استفاده از تگ {field-name} نمایش دهید.<br>
از این تک باید در فایل post.tpl قالب سایت خود استفاده کنید و به جای name باید نام انتخابی خود برای فیلد را قرار دهید.<br>
می توانید متناسب با نیاز خود بی نهایت فیلد اضافه برای پست ها تعیین کنید!!!
</div>
<!-- /option-id-4 -->

<div class="clear"></div>
</div>
</div>
<div class="content-bottom"></div>
</div>
<!-- TAB END-->
[/not-ajax]
