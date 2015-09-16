[show-list]
[list]
<table class="apadana-table" cellpadding="0" cellspacing="0">
  <tr>
    <th width="5">#</th>
    <th align="right">عنوان نظرسنجی</th>
    <th width="50">رای ها</th>
    <th width="60">تاریخ</th>
    <th width="30">وضعیت</th>
    <th width="30">عملیات</th>
  </tr>
[for list]
  <tr class="{odd-even}" id="vote-{id}">
    <td>{id}
	  <div style="display:none">
	  <input id="data-vote-id-{id}" type="hidden" value="{id}" />
	  <input id="data-vote-title-{id}" type="hidden" value="{title}" />
	  <input id="data-vote-button-{id}" type="hidden" value="{button}" />
	  <input id="data-vote-status-{id}" type="hidden" value="{status}" />
	  <input id="data-vote-date-{id}" type="hidden" value="{date}" />
	  <textarea id="data-vote-case-{id}">{case}</textarea>
	  <textarea id="data-vote-result-{id}">{result}</textarea>
	  </div>
	</td>
    <td align="right">{title}</td>
    <td>{total}</td>
    <td><img src="{site-url}engine/images/icons/clock.png" width="16" height="16" data-tooltip="{date}" ></td>
    <td><a href="javascript:voting_status({id})"><img src="{site-url}engine/images/icons/[status]plus-button[/status][not-status]minus-button[/not-status].png" width="16" height="16" data-tooltip="[status]فعال[/status][not-status]غیرفعال[/not-status]"  id="voting-status-{id}"></a></td>
    <td><a href="javascript:voting_edit({id})"><img src="{site-url}engine/images/icons/document-edit-icon.png" width="16" height="16" data-tooltip="ویرایش" ></a> <a href="javascript:voting_delete({id})"><img src="{site-url}engine/images/icons/cross-script.png" width="16" height="16" data-tooltip="حذف" ></a></td>
  </tr>
[/for list]
</table>
[/list]
[not-list]{function name="message" args="هیچ نظرسنجی وجود ندارد!|info"}[/not-list]
[/show-list]
[not-show-list]
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function voting_ajax(id)
{
    if (id == 1)
	{
		apadana.showID('form-new-voting');
		apadana.html('option-ajax-1', '');
 		apadana.hideID('more-case-1');
 		apadana.hideID('more-case-2');
 		apadana.hideID('more-case-3');
		apadana.$('form-new-voting').reset();
	}
	else if (id == 2)
	{
		apadana.ajax({
            method: 'get',
            action: '{admin-page}&module=voting&do=list',
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
        apadana.changeTab(2, 3, function(){voting_ajax(2)})
        alert('ابتدا یک نظرسنجی را برای ویرایش انتخاب کنید!');
	}
}
function voting_new()
{
	apadana.html('option-ajax-1', '');
	apadana.ajax({
		method: 'post',
		action: '{admin-page}&module=voting&do=new',
		data: apadana.serialize('form-new-voting'),
		success: function(data)
		{
			apadana.html('option-ajax-1', data);
		}
	})
}
function voting_edit(ID)
{
	apadana.html('option-ajax-3', '');
    if (ID=='save')
    {
        apadana.hideID('option-ajax-3');
		ID = apadana.value('vote-edit-id');
		apadana.ajax({
            method: 'post',
            action: '{admin-page}&module=voting&do=edit&id='+ID,
            data: apadana.serialize('form-edit-voting'),
            success: function(data)
            {
                apadana.showID("option-ajax-3");
			    apadana.html('option-ajax-3', data);
            }
		})
    }
    else
    {
 		apadana.hideID('edit-more-case-1');
 		apadana.hideID('edit-more-case-2');
 		apadana.hideID('edit-more-case-3');
		apadana.value('vote-edit-id', ID);
        apadana.value('vote-edit-title', apadana.value('data-vote-title-'+ID));
        apadana.value('vote-edit-button', apadana.value('data-vote-button-'+ID));
        apadana.html('vote-edit-date', apadana.value('data-vote-date-'+ID));
		
		if (apadana.value('data-vote-status-'+ID)==1)
		{
			apadana.$('vote-edit-status').checked='checked';
		}
		else
		{
			apadana.$('vote-edit-status').checked=false;
		}
		
        var cases = apadana.value('data-vote-case-'+ID).split('|');
        var result = apadana.value('data-vote-result-'+ID).split(',');

        var a=0,  b=0;
		var oForm = apadana.$('form-edit-voting');
        for(var i=0; i<oForm.elements.length; i++)
		{
         	var element = oForm.elements[i];

				if (!element.hasAttribute('name')) continue;

				if (element.name.indexOf('vote[case][') == 0)
				{
					var v = typeof(cases[a])=='undefined'? '' : cases[a];
					element.value = v;

					if (a>5 && v!='')
					{
						apadana.showID('edit-more-case-1')
					}
					if (a>8 && v!='')
					{
						apadana.showID('edit-more-case-2')
					}
					if (a>13 && v!='')
					{
						apadana.showID('edit-more-case-3')
					}					
					
					a++;
				}
				else if (element.name.indexOf('vote[result][') == 0)
				{
					var v = typeof(result[b])=='undefined'? 0 : result[b];
					element.value = typeof(cases[b])=='undefined' || cases[b]==''? '' : v;
					b++;
				}
        }
        apadana.changeTab(3, 3)
    }
}
function voting_status(ID)
{
    apadana.ajax({
        method: 'get',
		action: '{admin-page}&module=voting&do=status',
        data: 'id='+ID,
        success: function(status)
        {
    		status = apadana.trim(status);
    		if (status == 'active')
    		{
    			apadana.changeSrc('voting-status-'+ID, '{site-url}engine/images/icons/plus-button.png');
				apadana.attr('voting-status-'+ID, 'onmouseover','tooltip.show(\'فعال\')');
				apadana.value('data-vote-status-'+ID, 1);
    		}
    		else if (status == 'inactive')
    		{
    			apadana.changeSrc('voting-status-'+ID, '{site-url}engine/images/icons/minus-button.png');
				apadana.attr('voting-status-'+ID, 'onmouseover','tooltip.show(\'غیرفعال\')');
				apadana.value('data-vote-status-'+ID, 0);
    		}
    		else
			{
    			alert(status);
			}
	    }
    })
}
function voting_delete(ID)
{
    if (confirm('آیا از حذف این نظرسنجی اطمینان دارید؟'))
    apadana.ajax({
        method: 'get',
 		action: '{admin-page}&module=voting&do=delete',
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
  <li class="tab-on" id="tab-id-1" onclick="apadana.changeTab(1, 3, function(){voting_ajax(1)})">نظرسنجی جدید</li>
  <li class="tab-off" id="tab-id-2" onclick="apadana.changeTab(2, 3, function(){voting_ajax(2)})">فهرست نظرسنجی ها</li>
  <li class="tab-off" id="tab-id-3" onclick="apadana.changeTab(3, 3, function(){voting_ajax(3)})">ویرایش نظرسنجی</li>
</ul>
</div>
<div class="content-main">
<div class="content-space">

<div id="option-id-1" style="display:block">
<div id="option-ajax-1"></div>
<form id="form-new-voting" onsubmit="voting_new();return false">
<table cellpadding="6" cellspacing="0">
  <tr>
	<td width="90">عنوان</td>
	<td><input name="vote[title]" type="text" style="width:350px" lang="fa-ir" /></td>
  </tr>
  <tr>
	<td>گزینه ها</td>
	<td>
	1 = <input name="vote[case][1]" type="text" style="width:150px"><br><br>
	2 = <input name="vote[case][2]" type="text" style="width:150px"><br><br>
	3 = <input name="vote[case][3]" type="text" style="width:150px"><br><br>
	4 = <input name="vote[case][4]" type="text" style="width:150px"><br><br>
	5 = <input name="vote[case][5]" type="text" style="width:150px"><br><br>
	6 = <input name="vote[case][6]" type="text" style="width:150px"><br><br>
	7 = <input name="vote[case][7]" type="text" style="width:150px" onblur="if (this.value!='')apadana.showID('more-case-1')">
	<div id="more-case-1" style="display:none">
	<br>8 = <input name="vote[case][8]" type="text" style="width:150px"><br><br>
	9 = <input name="vote[case][9]" type="text" style="width:150px"><br><br>
	10 = <input name="vote[case][10]" type="text" style="width:144px" onblur="if (this.value!='')apadana.showID('more-case-2')">
	</div>
	<div id="more-case-2" style="display:none">
	<br>11 = <input name="vote[case][11]" type="text" style="width:144px"><br><br>
	12 = <input name="vote[case][12]" type="text" style="width:144px"><br><br>
	13 = <input name="vote[case][13]" type="text" style="width:144px"><br><br>
	14 = <input name="vote[case][14]" type="text" style="width:144px"><br><br>
	15 = <input name="vote[case][15]" type="text" style="width:144px" onblur="if (this.value!='')apadana.showID('more-case-3')">
	</div>
	<div id="more-case-3" style="display:none">
	<br>16 = <input name="vote[case][16]" type="text" style="width:144px"><br><br>
	17 = <input name="vote[case][17]" type="text" style="width:144px"><br><br>
	18 = <input name="vote[case][18]" type="text" style="width:144px"><br><br>
	19 = <input name="vote[case][19]" type="text" style="width:144px"><br><br>
	20 = <input name="vote[case][20]" type="text" style="width:144px">
	</div>
	</td>
  </tr>
  <tr>
	<td>دکمه ثبت</td>
	<td><input name="vote[button]" type="text" size="20" value="ثبت نظر" lang="fa-ir" /></td>
  </tr>
  <tr>
	<td>وضعیت</td>
	<td><label><input name="vote[status]" type="checkbox" value="1" checked="checked" />&nbsp;این نظرسنجی فعال باشد.</label></td>
  </tr>
  <tr>
	<td><input id="vote-cases-count" type="hidden" value="2"></td>
	<td><input type="submit" value="ذخیره نظرسنجی"> &nbsp; <input type="reset" value="از نو" onclick="voting_ajax(1)"></td>
  </tr>
</table>
</form>
</div>
<!-- /option-id-1 -->
<div id="option-id-2" style="display:none"></div>
<!-- /option-id-2 -->
<div id="option-id-3" style="display:none">
<div id="option-ajax-3"></div>
<form id="form-edit-voting" onsubmit="voting_edit('save');return false">
<table cellpadding="6" cellspacing="0">
  <tr>
	<td width="90">عنوان</td>
	<td><input name="vote[title]" id="vote-edit-title" type="text" size="50" lang="fa-ir" /></td>
  </tr>
  <tr>
	<td>تاریخ</td>
	<td id="vote-edit-date"></td>
  </tr>
  <tr>
	<td>گزینه ها</td>
	<td>
	1 = <input name="vote[case][1]" type="text" style="width:150px">&nbsp;&nbsp;&nbsp;رای ها&nbsp;<input name="vote[result][1]" type="text" style="width:20px;text-align:center"><br><br>
	2 = <input name="vote[case][2]" type="text" style="width:150px">&nbsp;&nbsp;&nbsp;رای ها&nbsp;<input name="vote[result][2]" type="text" style="width:20px;text-align:center"><br><br>
	3 = <input name="vote[case][3]" type="text" style="width:150px">&nbsp;&nbsp;&nbsp;رای ها&nbsp;<input name="vote[result][3]" type="text" style="width:20px;text-align:center"><br><br>
	4 = <input name="vote[case][4]" type="text" style="width:150px">&nbsp;&nbsp;&nbsp;رای ها&nbsp;<input name="vote[result][4]" type="text" style="width:20px;text-align:center"><br><br>
	5 = <input name="vote[case][5]" type="text" style="width:150px">&nbsp;&nbsp;&nbsp;رای ها&nbsp;<input name="vote[result][5]" type="text" style="width:20px;text-align:center"><br><br>
	6 = <input name="vote[case][6]" type="text" style="width:150px">&nbsp;&nbsp;&nbsp;رای ها&nbsp;<input name="vote[result][6]" type="text" style="width:20px;text-align:center"><br><br>
	7 = <input name="vote[case][7]" type="text" style="width:150px" onblur="if (this.value!='')apadana.showID('edit-more-case-1')">&nbsp;&nbsp;&nbsp;رای ها&nbsp;<input name="vote[result][7]" type="text" style="width:20px;text-align:center">
	<div id="edit-more-case-1" style="display:none">
	<br>8 = <input name="vote[case][8]" type="text" style="width:150px">&nbsp;&nbsp;&nbsp;رای ها&nbsp;<input name="vote[result][8]" type="text" style="width:20px;text-align:center"><br><br>
	9 = <input name="vote[case][9]" type="text" style="width:150px">&nbsp;&nbsp;&nbsp;رای ها&nbsp;<input name="vote[result][9]" type="text" style="width:20px;text-align:center"><br><br>
	10 = <input name="vote[case][10]" type="text" style="width:144px" onblur="if (this.value!='')apadana.showID('edit-more-case-2')">&nbsp;&nbsp;&nbsp;رای ها&nbsp;<input name="vote[result][10]" type="text" style="width:20px;text-align:center">
	</div>
	<div id="edit-more-case-2" style="display:none">
	<br>11 = <input name="vote[case][11]" type="text" style="width:144px">&nbsp;&nbsp;&nbsp;رای ها&nbsp;<input name="vote[result][11]" type="text" style="width:20px;text-align:center"><br><br>
	12 = <input name="vote[case][12]" type="text" style="width:144px">&nbsp;&nbsp;&nbsp;رای ها&nbsp;<input name="vote[result][12]" type="text" style="width:20px;text-align:center"><br><br>
	13 = <input name="vote[case][13]" type="text" style="width:144px">&nbsp;&nbsp;&nbsp;رای ها&nbsp;<input name="vote[result][13]" type="text" style="width:20px;text-align:center"><br><br>
	14 = <input name="vote[case][14]" type="text" style="width:144px">&nbsp;&nbsp;&nbsp;رای ها&nbsp;<input name="vote[result][14]" type="text" style="width:20px;text-align:center"><br><br>
	15 = <input name="vote[case][15]" type="text" style="width:144px" onblur="if (this.value!='')apadana.showID('edit-more-case-3')">&nbsp;&nbsp;&nbsp;رای ها&nbsp;<input name="vote[result][15]" type="text" style="width:20px;text-align:center">
	</div>
	<div id="edit-more-case-3" style="display:none">
	<br>16 = <input name="vote[case][16]" type="text" style="width:144px">&nbsp;&nbsp;&nbsp;رای ها&nbsp;<input name="vote[result][16]" type="text" style="width:20px;text-align:center"><br><br>
	17 = <input name="vote[case][17]" type="text" style="width:144px">&nbsp;&nbsp;&nbsp;رای ها&nbsp;<input name="vote[result][17]" type="text" style="width:20px;text-align:center"><br><br>
	18 = <input name="vote[case][18]" type="text" style="width:144px">&nbsp;&nbsp;&nbsp;رای ها&nbsp;<input name="vote[result][18]" type="text" style="width:20px;text-align:center"><br><br>
	19 = <input name="vote[case][19]" type="text" style="width:144px">&nbsp;&nbsp;&nbsp;رای ها&nbsp;<input name="vote[result][19]" type="text" style="width:20px;text-align:center"><br><br>
	20 = <input name="vote[case][20]" type="text" style="width:144px">&nbsp;&nbsp;&nbsp;رای ها&nbsp;<input name="vote[result][20]" type="text" style="width:20px;text-align:center">
	</div>
	</td>
  </tr>
  <tr>
	<td>دکمه ثبت</td>
	<td><input name="vote[button]" id="vote-edit-button" type="text" size="20" lang="fa-ir" /></td>
  </tr>
  <tr>
	<td>وضعیت</td>
	<td><label><input name="vote[status]" type="checkbox" value="1" id="vote-edit-status" />&nbsp;این نظرسنجی فعال باشد.</label></td>
  </tr>
  <tr>
	<td><input id="vote-edit-id" type="hidden" value="0"></td>
	<td><input type="submit" value="ویرایش نظرسنجی"></td>
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