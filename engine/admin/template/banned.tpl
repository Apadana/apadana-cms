[show-list]
[list]
<table class="apadana-table" cellpadding="0" cellspacing="0">
  <tr>
    <th style="width:10px">#</th>
    <th style="text-align:right">آی پی</th>
    <th style="width:350px">تاریخ</th>
    <th style="width:50px">عملیات</th>
  </tr>
[for list]
  <tr class="{odd-even}" id="banned-{id}">
    <td>{id}
	  <div style="display:none">
	  <input id="data-banned-ip-1-{id}" type="hidden" value="{ip-1}" />
	  <input id="data-banned-ip-2-{id}" type="hidden" value="{ip-2}" />
	  <input id="data-banned-ip-3-{id}" type="hidden" value="{ip-3}" />
	  <input id="data-banned-ip-4-{id}" type="hidden" value="{ip-4}" />
	  <textarea id="data-banned-reason-{id}">{reason}</textarea>
	  </div>
	</td>
    <td dir="ltr" style="text-align:right"><span onmouseover="tooltip.show('{reason}')" onmouseout="tooltip.hide()">{ip}</span></td>
    <td>{date}</td>
    <td><a href="javascript:banned_edit({id})"><img src="{site-url}engine/images/icons/document-edit-icon.png" width="16" height="16" onmouseover="tooltip.show('ویرایش')" onmouseout="tooltip.hide()"></a> <a href="javascript:banned_delete({id})"><img src="{site-url}engine/images/icons/cross-script.png" width="16" height="16" onmouseover="tooltip.show('حذف')" onmouseout="tooltip.hide()"></a></td>
  </tr>
[/for list]
</table>
[/list]
[not-list]{function name="message" args="هیچ آی پی وجود ندارد!|info"}[/not-list]
[/show-list]
[not-show-list]
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function banned_ajax(id)
{
    if (id == 1)
	{
	    apadana.html('option-ajax-1', '');
        apadana.showID("form-new-banned");
		apadana.$("form-new-banned").reset();
	}
	else if (id == 2)
	{
		apadana.ajax({
            method: 'get',
            action: '{admin-page}&section=banned&do=list',
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
        apadana.changeTab(2, 4, function(){banned_ajax(2)})
        alert('ابتدا یک آی پی را برای ویرایش انتخاب کنید!');
	}
}
function banned_new()
{
    apadana.html("option-ajax-1", '');
	apadana.ajax({
		method: 'post',
		action: '{admin-page}&section=banned&do=new',
		data: apadana.serialize('form-new-banned'),
		success: function(data)
		{
		    apadana.html('option-ajax-1', data);
		}
	})
}
function banned_edit(ID)
{
	apadana.html("option-ajax-3", '');
    if (ID=='save')
    {
		ID = apadana.value('banned-edit-id');
		apadana.ajax({
            method: 'post',
            action: '{admin-page}&section=banned&do=edit&id='+ID,
            data: apadana.serialize('form-edit-banned'),
            success: function(data)
            {
			    apadana.html('option-ajax-3', data);
            }
		})
    }
    else
    {
 		apadana.value('banned-edit-id', ID);
 		apadana.value('banned-edit-ip-1', apadana.value('data-banned-ip-1-'+ID));
 		apadana.value('banned-edit-ip-2', apadana.value('data-banned-ip-2-'+ID));
 		apadana.value('banned-edit-ip-3', apadana.value('data-banned-ip-3-'+ID));
 		apadana.value('banned-edit-ip-4', apadana.value('data-banned-ip-4-'+ID));
 		apadana.value('banned-edit-reason', apadana.value('data-banned-reason-'+ID));
        apadana.changeTab(3, 4)
    }
}
function banned_delete(ID)
{
    if (confirm("آیا از حذف این آی پی اطمینان دارید؟"))
    apadana.ajax({
        method: 'get',
        action: '{admin-page}&section=banned&do=delete',
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
  <li class="tab-on" id="tab-id-1" onclick="apadana.changeTab(1, 4, function(){banned_ajax(1)})">آی پی جدید</li>
  <li class="tab-off" id="tab-id-2" onclick="apadana.changeTab(2, 4, function(){banned_ajax(2)})">مدیریت آی پی ها</li>
  <li class="tab-off" id="tab-id-3" onclick="apadana.changeTab(3, 4, function(){banned_ajax(3)})">ویرایش آی پی</li>
  <li class="tab-off" id="tab-id-4" onclick="apadana.changeTab(4, 4)">راهنما</li>
</ul>
</div>
<div class="content-main">
<div class="content-space">

<div id="option-id-1" style="display:block">
<div id="option-ajax-1"></div>
<form id="form-new-banned" onsubmit="banned_new();return false">
<table cellpadding="6" cellspacing="0">
  <tr>
	<td width="80">آی پی</td>
	<td dir="ltr"><input name="banned[ip][1]" type="text" style="width:25px;text-align:center">.<input name="banned[ip][2]" type="text" style="width:25px;text-align:center">.<input name="banned[ip][3]" type="text" style="width:25px;text-align:center">.<input name="banned[ip][4]" type="text" style="width:25px;text-align:center"></td>
  </tr>
  <tr>
	<td>دلیل</td>
	<td><textarea name="banned[reason]" lang="fa" style="width:70%;height:100px"></textarea></td>
  </tr>
  <tr>
	<td></td>
	<td><input type="submit" value="مسدود سازی آی پی" /> &nbsp; <input type="reset" value="از نو" onclick="apadana.html('option-ajax-1', '')" /></td>
  </tr>
</table>
</form>
</div>
<!-- /option-id-1 -->
<div id="option-id-2" style="display:none"></div>
<!-- /option-id-2 -->
<div id="option-id-3" style="display:none">
<div id="option-ajax-3"></div>
<form id="form-edit-banned" onsubmit="banned_edit('save');return false">
<table cellpadding="6" cellspacing="0">
  <tr>
	<td width="80">آی پی</td>
	<td><input name="banned[ip][1]" type="text" style="width:25px;text-align:center" id="banned-edit-ip-1">.<input name="banned[ip][2]" type="text" style="width:25px;text-align:center" id="banned-edit-ip-2">.<input name="banned[ip][3]" type="text" style="width:25px;text-align:center" id="banned-edit-ip-3">.<input name="banned[ip][4]" type="text" style="width:25px;text-align:center" id="banned-edit-ip-4"></td>
  </tr>
  <tr>
	<td>دلیل</td>
	<td><textarea name="banned[reason]" id="banned-edit-reason" lang="fa" style="width:70%;height:100px"></textarea></td>
  </tr>
  <tr>
	<td></td>
	<td><input id="banned-edit-id" type="hidden" value="0" /><input type="submit" name="submit" value="ویرایش آی پی" /></td>
  </tr>
</table>
</form>
</div>
<!-- /option-id-3 -->
<div id="option-id-4" style="display:none">
<strong>راهنمای استفاده از بخش مسدود سازی آی پی</strong><br />
با استفاده از این بخش می توانید آی پی کاربرانی که در سایت شما مشکل ایجاد می کنند را مسدود کنید تا تنوانند به سایت دسترسی پیدا کنند.<br>
در قسمت های سوم و چهارم آی پی می توانید از * استفاده کنید.
</div>
</td>
<!-- /option-id-4 -->

<div class="clear"></div>
</div>
</div>
<div class="content-bottom"></div>
</div>
<!-- TAB END-->
[/not-show-list]