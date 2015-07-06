[show-groups]
<table class="apadana-table" cellpadding="0" cellspacing="0">
<thead>
  <tr>
    <th width="15">#</th>
    <th align="right">عنوان گروه</th>
    <th width="80">تعداد کاربران</th>
    <th width="50">مدیر</th>
    <th width="50">مدیر کل</th>
    <th width="50">عملیات</th>
  </tr>
</thead>
<tbody>
[for groups]
	  <tr class="{odd-even}">
	    <td>{id}
		<div style="display:none">
		<input id="data-group-id-{id}" type="hidden" value="{id}" />

		<input id="data-group-name-{id}" type="hidden" value="{name}" />
		<input id="data-group-icon-{id}" type="hidden" value="{icon}" />
		<input id="data-group-admin-{id}" type="hidden" value="{data-admin}" />
		<input id="data-group-superAdmin-{id}" type="hidden" value="{data-superAdmin}" />
		<textarea id="data-group-rights-{id}">{data-rights}</textarea>
		<textarea id="data-group-title-{id}">{title}</textarea>
		</div>
		</td>
	    <td align="right">{name}</td>
	    <td>{members}</td>
	    <td><img src="{site-url}engine/images/icons/[group-admin]tick-button[/group-admin][not-group-admin]minus-button[/not-group-admin].png" onmouseover="tooltip.show('[group-admin]کاربران این گروه مدیران سایت هستند[/group-admin][not-group-admin]کاربرن گروه به بخش مدیریت دسترسی ندارند[/not-group-admin]')" onmouseout="tooltip.hide()"></td>
	    <td><img src="{site-url}engine/images/icons/[group-super-admin]crown[/group-super-admin][not-group-super-admin]minus-button[/not-group-super-admin].png" onmouseover="tooltip.show('[group-super-admin]کاربران این گروه در سایت دسترسی مدیرکل دارند[/group-super-admin][not-group-super-admin]کاربران این گروه دسترسی مدیرکل را ندارند[/not-group-super-admin]')" onmouseout="tooltip.hide()"></td>
	    <td><a href="javascript:groups_edit({id})"><img src="{site-url}engine/images/icons/document-edit-icon.png" onmouseover="tooltip.show('ویرایش گروه')" onmouseout="tooltip.hide()"></a>&nbsp;[group-delete]<a href="javascript:groups_delete({id})"><img src="{site-url}engine/images/icons/cross-script.png" onmouseover="tooltip.show('حذف گروه')" onmouseout="tooltip.hide()"></a>[/group-delete][not-group-delete]<img src="{site-url}engine/images/icons/cross-script-x.png" onmouseover="tooltip.show('امکان حذف این گروه وجود ندارد!')" onmouseout="tooltip.hide()">[/not-group-delete]</td>
	  </tr>
[/for groups]
</tbody>
</table>
[/show-groups]
[not-show-groups]
<script type="text/javascript">
function groups_ajax(id)
{
    if(id == 1)
	{
	    apadana.html('option-ajax-1', '');
        apadana.showID('form-new-group');
        apadana.$('form-new-group').reset();
	}
	else if(id == 2)
	{
		apadana.ajax({
			type: 'GET',
			action: '{admin-page}&section=groups&do=list',
            loading: 'no',
			beforeSend: function()
			{
				apadana.html('option-id-2', '<p><center><img src="{site-url}engine/images/loading/loader-9.gif" width="54" height="55"></center></p>');
			},
			success: function(data)
			{
        		if(!apadana.browser.ie)
        		apadana.fadeOut('option-id-2', function(){
					apadana.html('option-id-2', data);
					apadana.fadeIn('option-id-2');
				});
				else
				apadana.html('option-id-2', data);
			}
		})
	}
	else if(id == 3)
	{
        apadana.changeTab(2, 3, function(){groups_ajax(2)})
        alert('ابتدا یک گروه را برای ویرایش انتخاب کنید!');
	}
}
function groups_new()
{
	apadana.html('option-ajax-1', '');
    apadana.ajax({
        type: 'POST',
        action: '{admin-page}&section=groups&do=new',
        data: apadana.serialize('form-new-group'),
        id: 'option-ajax-1'
    });
}
function groups_edit(ID)
{
    if(ID=='save')
    {
		ID = apadana.value('group-edit-id');
		apadana.html('option-ajax-3', '');
		apadana.ajax({
			type: 'POST',
			action: '{admin-page}&section=groups&do=edit&c='+ID,
			data: apadana.serialize('form-edit-group'),
			success: function(data)
			{
				apadana.html('option-ajax-3', data);
			}
		});
    }
    else
    {
        apadana.html('option-ajax-3', '');
 		apadana.value('group-edit-id', apadana.value('data-group-id-'+ID));
 		apadana.value('group-edit-name', apadana.value('data-group-name-'+ID));
 		apadana.value('group-edit-icon', apadana.value('data-group-icon-'+ID));
 		apadana.value('group-edit-title', apadana.value('data-group-title-'+ID));
		
		if(apadana.value('data-group-admin-'+ID)==1)
		{
			apadana.$('group-edit-admin-1').checked='checked';
			apadana.$('group-edit-admin-0').checked=false;
		}
		else
		{
			apadana.$('group-edit-admin-1').checked=false;
			apadana.$('group-edit-admin-0').checked='checked';
		}		

		if(apadana.value('data-group-superAdmin-'+ID)==1)
		{
			apadana.$('group-edit-superAdmin-1').checked='checked';
			apadana.$('group-edit-superAdmin-0').checked=false;
		}
		else
		{
			apadana.$('group-edit-superAdmin-1').checked=false;
			apadana.$('group-edit-superAdmin-0').checked='checked';
		}

		var element = apadana.$('group-edit-rights');
		var rights = apadana.value('data-group-rights-'+ID);
		var rights = rights.split(',');

		var length = element.length;
		if(length > 0)
		{
			for(var b = 0; b < length; b++)
			{
				var opt = element.options[b];

				if(!apadana.browser.ie)
				{
					if(rights.indexOf(opt.value) != -1)
					{
						opt.selected = 'selected';
					}
					else
					{
						opt.selected = '';
					}
				}
				else
				{
					var r_length = rights.length;
					var selected = false;
					for(var x = 0; x < r_length; x++)
					{
						if(rights[x] == opt.value)
						{
							var selected = 'selected';
							break;
						}
					}
					opt.selected = selected;
				}
			}
		}

        apadana.changeTab(3, 3)
    }
}
function groups_delete(ID)
{
    if(!confirm("آیا از حذف این گروه اطمینان دارید؟\n"+apadana.value('data-group-name-'+ID))) return;
    apadana.ajax({
        type: 'POST',
        action: '{admin-page}&section=groups&do=delete&c='+ID+'&d={token}',
        json: 'yes',
        success: function(data)
        {
            if(data.result == 'SUCCESS')
            	groups_ajax(2)
            else
            	alert('خطایی رخ داده مجدد تلاش کنید');
        }
    });
}
</script>

<!-- TAB START-->
<div class="content">
<div class="content-tabs">
<ul>
  <li class="tab-on" id="tab-id-1" onclick="apadana.changeTab(1, 3, function(){groups_ajax(1)})">ساختن گروه جدید</li>
  <li class="tab-off" id="tab-id-2" onclick="apadana.changeTab(2, 3, function(){groups_ajax(2)})">لیست گروه ها</li>
  <li class="tab-off" id="tab-id-3" onclick="apadana.changeTab(3, 3, function(){groups_ajax(3)})">ویرایش گروه</li>
</ul>
</div>
<div class="content-main">
<div class="content-space">

<div id="option-id-1" style="display:block">
<div id="option-ajax-1"></div>
<form id="form-new-group" onsubmit="groups_new();return false">
<table cellspacing="5">
  <tr>
	<td width="110">عنوان گروه</td>
	<td><input name="groups[name]" type="text" value="" style="width:98.5%" /></td>
  </tr>
  <tr>
	<td>عکس گروه</td>
	<td><input name="groups[icon]" type="text" value="" style="width:98.5%" dir=ltr /></td>
  </tr>
  <tr>
	<td>استایل عنوان گروه</td>
	<td><textarea name="groups[title]" style="width:98.5%;height:50px" dir=ltr>{name}</textarea></td>
  </tr>
  <tr>
	<td>دسترسی به بخش های مدیریت</td>
	<td>{select}</td>
  </tr>
  <tr>
	<td>این گروه مدیر باشند</td>
	<td><label><input type="radio" name="groups[admin]" value="1" id="groups-damin-radio" />بله</label> <label><input type="radio" name="groups[admin]" value="0" checked="checked" onclick="if(apadana.$('groups-superAdmin-radio').checked) apadana.$('groups-damin-radio').checked='checked';" />خیر</label>&nbsp;<font color="#AAAAAA" size="1">(در صورتی که این گزینه فعال باشد این گروه به بخش مدیریت دسترسی خواهند داشت!)</font></td>
  </tr>
  <tr>
	<td>این گروه مدیرکل باشند</td>
	<td><label><input type="radio" name="groups[superAdmin]" value="1" onclick="apadana.$('groups-damin-radio').checked='checked';" id="groups-superAdmin-radio" />بله</label> <label><input type="radio" name="groups[superAdmin]" value="0" checked="checked" />خیر</label>&nbsp;<font color="#AAAAAA" size="1">(در صورتی که این بخش را فعال کنید کاربرهای این گروه به همه ی بخش ها دسترسی خواهند داشت!)</font></td>
  </tr>
  <tr>
	<td></td>
	<td><input name="groups[token]" type="hidden" value="{token}" /><input type="submit" value="ساختن گروه جدید" />&nbsp;<input type="reset" value="پاک کردن فرم" /></td>
  </tr>
</table>
</form>
</div>
<!-- /option-id-1 -->
<div id="option-id-2" style="display:none"></div>
<!-- /option-id-2 -->
<div id="option-id-3" style="display:none">
<div id="option-ajax-3"></div>
<form id="form-edit-group" onsubmit="groups_edit('save');return false">
<table cellspacing="5">
  <tr>
	<td width="110">عنوان گروه</td>
	<td><input name="groups[name]" type="text" value="" id="group-edit-name" style="width:98.5%" /></td>
  </tr>
  <tr>
	<td>عکس گروه</td>
	<td><input name="groups[icon]" type="text" value="" id="group-edit-icon" style="width:98.5%" dir=ltr /></td>
  </tr>
  <tr>
	<td>استایل عنوان گروه</td>
	<td><textarea name="groups[title]" id="group-edit-title" style="width:98.5%;height:50px" dir=ltr></textarea></td>
  </tr>
  <tr>
	<td>دسترسی به بخش های مدیریت</td>
	<td>{edit-select}</td>
  </tr>
  <tr>
	<td>این گروه مدیر باشند</td>
	<td><label><input type="radio" name="groups[admin]" value="1" id="group-edit-admin-1" />بله</label> <label><input type="radio" name="groups[admin]" value="0" id="group-edit-admin-0" checked="checked" onclick="if(apadana.$('group-edit-superAdmin-1').checked) apadana.$('group-edit-admin-1').checked='checked';" />خیر</label>&nbsp;<font color="#AAAAAA" size="1">(در صورتی که این گزینه فعال باشد این گروه به بخش مدیریت دسترسی خواهند داشت!)</font></td>
  </tr>
  <tr>
	<td>این گروه مدیرکل باشند</td>
	<td><label><input type="radio" name="groups[superAdmin]" value="1" id="group-edit-superAdmin-1" onclick="apadana.$('group-edit-admin-1').checked='checked';" />بله</label> <label><input type="radio" name="groups[superAdmin]" value="0" id="group-edit-superAdmin-0" checked="checked" />خیر</label>&nbsp;<font color="#AAAAAA" size="1">(در صورتی که این بخش را فعال کنید کاربرهای این گروه به همه ی بخش ها دسترسی خواهند داشت!)</font></td>
  </tr>
  <tr>
	<td><input id="group-edit-id" type="hidden" value="0" /><input name="groups[token]" type="hidden" value="{token}" /></td>
	<td><input type="submit" value="ویرایش گروه" /></td>
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
[/not-show-groups]