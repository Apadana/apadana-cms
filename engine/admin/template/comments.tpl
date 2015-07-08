[not-ajax]
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function comment_ajax(ID)
{
	if (ID == 1)
	{
		apadana.ajax({
			method: 'get',
			action: '{admin-page}&section=comments&'+apadana.serialize('form-options-show'),
			id: 'option-id-1'
		})
	}
	else if (ID == 2)
	{
        apadana.changeTab(1, 2)
        alert('ابتدا یک نظر را برای ویرایش انتخاب کنید!');
	}
}
function comment_edit(ID)
{
	apadana.html('option-ajax-2', '');
	if (ID == 'save')
    {
		apadana.value('textarea_comment_text', CKEDITOR.instances.textarea_comment_text.getData());
		apadana.value('textarea_comment_answer', CKEDITOR.instances.textarea_comment_answer.getData());	
		ID = apadana.value('comment-edit-id');
		apadana.ajax({
			method: 'post',
			action: '{admin-page}&section=comments&do=edit&id='+ID,
			data: apadana.serialize('form-edit-comments'),
			success: function(data)
			{
				apadana.html('option-ajax-2', data);

				if (data.substr(0, 9) == '<!--OK-->')
				{
					// update data
					apadana.value('data-comment-author-'+ID, apadana.value('comment-edit-author'));
					apadana.value('data-comment-author-email-'+ID, apadana.value('comment-edit-author-email'));
					apadana.value('data-comment-author-url-'+ID, apadana.value('comment-edit-author-url'));
					apadana.value('data-comment-approve-'+ID, apadana.$('comment-edit-approve').checked? 1 : 0);
					apadana.value('data-comment-text-'+ID, CKEDITOR.instances.textarea_comment_text.getData());
					apadana.value('data-comment-answer-'+ID, CKEDITOR.instances.textarea_comment_answer.getData());
					
					// update list
					if (apadana.$('comment-edit-approve').checked)
					{
						apadana.changeSrc('comment-approve-'+ID, '{site-url}engine/images/icons/plus-button.png');
						apadana.attr('comment-approve-'+ID, 'onmouseover','tooltip.show(\'تایید\')');
						apadana.attr('comment-approve-'+ID, 'approve','1');
						apadana.attr('tr-comment-'+ID, 'style', null);
						apadana.value('data-comment-approve-'+ID, '1')
					}
					else
					{
						apadana.changeSrc('comment-approve-'+ID, '{site-url}engine/images/icons/minus-button.png');
						apadana.attr('comment-approve-'+ID, 'onmouseover','tooltip.show(\'تایید نشده\')');
						apadana.attr('comment-approve-'+ID, 'approve','0');
						apadana.attr('tr-comment-'+ID, 'style', 'background:#FFFFCC');
						apadana.value('data-comment-approve-'+ID, '0')
					}
				}
			}
		})
    }
    else
    {
 		apadana.value('comment-edit-id', ID);
 		apadana.value('comment-edit-author', apadana.value('data-comment-author-'+ID));
 		apadana.value('comment-edit-author-email', apadana.value('data-comment-author-email-'+ID));
 		apadana.value('comment-edit-author-url', apadana.value('data-comment-author-url-'+ID));

		if (apadana.value('data-comment-member-name-'+ID) == '')
		{
			apadana.html('comment-edit-member', 'کاربر نویسنده نظر مهمان بوده است!');
		}
		else
		{
			apadana.html('comment-edit-member', 'این نظر توسط <a href="{site-url}?a=account&b=profile&c='+apadana.value('data-comment-member-name-'+ID)+'" onmouseover="tooltip.show(\'مشاهده پروفایل\')" onmouseout="tooltip.hide()" target="_blank"><b>'+apadana.value('data-comment-member-name-'+ID)+'</b></a> ارسال شده است.');
		}
		
		if (apadana.value('data-comment-approve-'+ID) == 1)
		{
			apadana.$('comment-edit-approve').checked = 'checked';
		}
		else
		{
			apadana.$('comment-edit-approve').checked = false;
		}

 		apadana.html('comment-edit-author-ip', apadana.value('data-comment-author-ip-'+ID));
 		apadana.html('comment-edit-date', '<span onmouseover="tooltip.show(\''+apadana.value('data-comment-past-time-'+ID)+'\')" onmouseout="tooltip.hide()">ارسال شده در '+apadana.value('data-comment-date-'+ID)+'</span>');
 		apadana.attr('comment-edit-link', 'href', apadana.value('data-comment-url-'+ID));

		CKEDITOR.instances.textarea_comment_text.setData( apadana.value('data-comment-text-'+ID) );
		CKEDITOR.instances.textarea_comment_answer.setData( apadana.value('data-comment-answer-'+ID) );
		apadana.changeTab(2, 2)
    }
}
function comment_approve(ID)
{
	apadana.ajax({
		method: 'get',
		action: '{admin-page}&section=comments&do=approve',
		data: 'id='+ID,
		success: function(approve)
		{
			approve = apadana.trim(approve);
			if (approve == 'ok')
			{
				apadana.changeSrc('comment-approve-'+ID, '{site-url}engine/images/icons/plus-button.png');
				apadana.attr('comment-approve-'+ID, 'onmouseover','tooltip.show(\'تایید\')');
				apadana.attr('tr-comment-'+ID, 'style', '');
				apadana.value('data-comment-approve-'+ID, '1')
			}
			else if (approve == 'no')
			{
				apadana.changeSrc('comment-approve-'+ID, '{site-url}engine/images/icons/minus-button.png');
				apadana.attr('comment-approve-'+ID, 'onmouseover','tooltip.show(\'تایید نشده\')');
				apadana.attr('tr-comment-'+ID, 'style', 'background:#FFFFCC');
				apadana.value('data-comment-approve-'+ID, '0')
			}
			else
				alert(approve);
		}
	})
}
function comment_delete(ID)
{
	if (confirm('آیا از حذف این نظر اطمینان دارید؟'))
	apadana.ajax({
		method: 'get',
		action: '{admin-page}&section=comments&do=delete',
		data: 'id='+ID,
		id: 'option-id-1',
	})
}
/*]]>*/
</script>

<!-- TAB START-->
<div class="content">
<div class="content-tabs">
<ul>
  <li class="tab-on" id="tab-id-1" onclick="apadana.changeTab(1, 2)">فهرست نظرات</li>
  <li class="tab-off" id="tab-id-2" onclick="apadana.changeTab(2, 2, function(){comment_ajax(2)})">ویرایش نظر</li>
</ul>
</div>
<div class="content-main">
<div class="content-space">

<div id="option-id-1" style="display:block">
[/not-ajax]
<form id="form-options-show" class="fast-panel">
چینش&nbsp;&raquo;&nbsp;
<select name="order" size="1">
<option value="DESC"[desc] selected="selected"[/desc]>نزولی</option>
<option value="ASC"[asc] selected="selected"[/asc]>صعودی</option>
</select>
&nbsp;&nbsp;تعداد نظرات در صفحه&nbsp;&raquo;&nbsp;
<input name="total" type="text" style="width:25px;text-align:center" value="{total}" maxlength="3" />
&nbsp;&nbsp;بخش&nbsp;&raquo;&nbsp;
<select name="type" size="1">
[for types]<option value="{name}"[selected] selected="selected"[/selected]>{title}</option>[/for types]
</select>
[pages]
&nbsp;&nbsp;صفحات&nbsp;&raquo;&nbsp;
<select name="page" size="1">
[for pages]<option value="{number}"[selected] selected="selected"[/selected]>{number}</option>[/for pages]
</select>
[/pages]
&nbsp;&nbsp;
<input type="button" value="نمایش" onclick="comment_ajax(1)" />
</form>
[comments]
<table class="apadana-table" cellpadding="0" cellspacing="0">
  <tr>
	<th width="5">#</th>
	<th width="60">نویسنده</th>
	<th align="right">خلاصه نظر</th>
	<th width="30">تاریخ</th>
	<th width="30">وضعیت</th>
	<th width="20">کاربر</th>
	<th width="20">عملیات</th>
  </tr>[for comments]
  <tr class="{odd-even}" id="tr-comment-{id}"[not-approve] style="background:#FFFFCC"[/not-approve]>
	<td>{id}
	<div style="display:none">
	<input id="data-comment-id-{id}" type="hidden" value="{id}" />
	<input id="data-comment-link-{id}" type="hidden" value="{link}" />
	<input id="data-comment-url-{id}" type="hidden" value="{url}" />
	<input id="data-comment-member-id-{id}" type="hidden" value="{member-id}" />
	<input id="data-comment-member-name-{id}" type="hidden" value="{member-name}" />
	<input id="data-comment-author-{id}" type="hidden" value="{author}" />
	<input id="data-comment-author-email-{id}" type="hidden" value="{author-email}" />
	<input id="data-comment-author-url-{id}" type="hidden" value="{author-url}" />
	<input id="data-comment-author-ip-{id}" type="hidden" value="{author-ip}" />
	<input id="data-comment-date-{id}" type="hidden" value="{date}" />
	<input id="data-comment-past-time-{id}" type="hidden" value="{past-time}" />
	<input id="data-comment-answer-author-{id}" type="hidden" value="{answer-author}" />
	<input id="data-comment-approve-{id}" type="hidden" value="{approve}" />
	<input id="data-comment-language-{id}" type="hidden" value="{language}" />
	<textarea id="data-comment-text-{id}">{text}</textarea>
	<textarea id="data-comment-answer-{id}">{answer}</textarea>
	</div>
	</td>
	<td>{author}</td>
	<td align="right">{msg}</td>
	<td><img src="{site-url}engine/images/icons/calendar-day.png" width="16" height="16" onmouseover="tooltip.show('ارسال شده در [approve]{date}[/approve][not-approve]{past-time}[/not-approve]')" onmouseout="tooltip.hide()" /></td>
	<td><a href="javascript:comment_approve({id})"><img src="{site-url}engine/images/icons/[approve]plus-button[/approve][not-approve]minus-button[/not-approve].png" width="16" height="16" onmouseover="tooltip.show('[approve]تایید شده[/approve][not-approve]تایید نشده[/not-approve]')" onmouseout="tooltip.hide()" id="comment-approve-{id}" /></a></td>
	<td>[member-name]<span onmouseover="tooltip.show('این نظر توسط این کاربر ارسال شده است')" onmouseout="tooltip.hide()">{member-name}</span>[/member-name][not-member-name]--[/not-member-name]</td>
	<td><a href="javascript:comment_edit({id})"><img src="{site-url}engine/images/icons/document-edit-icon.png" width="16" height="16" onmouseover="tooltip.show('ویرایش')" onmouseout="tooltip.hide()"></a> <a href="javascript:comment_delete({id})"><img src="{site-url}engine/images/icons/cross-script.png" width="16" height="16" onmouseover="tooltip.show('حذف')" onmouseout="tooltip.hide()"></a></td>
  </tr>
[/for comments]
</table>
[/comments]
[not-comments]{function name="message" args="هیچ نظری ثبت نشده است!|error"}[/not-comments]
[not-ajax]
</div>
<!-- /option-id-1 -->
<div id="option-id-2" style="display:none">
<div id="option-ajax-2"></div>
<form id="form-edit-comments" onsubmit="comment_edit('save');return false">
<table cellspacing="5">
  <tr>
	<td width="110">نویسنده</td>
	<td><input name="comment[author]" type="text" value="" id="comment-edit-author" style="width:300px" /></td>
  </tr>
  <tr>
	<td>ایمیل نویسنده</td>
	<td><input name="comment[author-email]" type="text" value="" id="comment-edit-author-email" dir="ltr" style="width:300px" /></td>
  </tr>
  <tr>
	<td>وب سایت نویسنده</td>
	<td><input name="comment[author-url]" type="text" value="" id="comment-edit-author-url" dir="ltr" style="width:300px" /></td>
  </tr>
  <tr>
	<td>کاربر</td>
	<td id="comment-edit-member"></td>
  </tr>
  <tr>
	<td>آی پی نویسنده</td>
	<td id="comment-edit-author-ip"></td>
  </tr>
  <tr>
	<td>تاریخ ارسال</td>
	<td id="comment-edit-date"></td>
  </tr>
  <tr>
	<td>متن</td>
	<td>{textarea-text}</td>
  </tr>
  <tr>
	<td>پاسخ</td>
	<td>{textarea-answer}</td>
  </tr>
  <tr>
	<td>تایید</td>
	<td><label><input type="checkbox" name="comment[approve]" value="1" id="comment-edit-approve"  />&nbsp;این نظر را تایید می کنم.</label></td>
  </tr>
  <tr>
	<td></td>
	<td><a id="comment-edit-link" target="_blank">مشاهده مطلب مرتبط با این نظر</a></td>
  </tr>
  <tr>
	<td><input id="comment-edit-id" type="hidden" value="0" /></td>
	<td><input type="submit" value="ویرایش نظر" /></td>
  </tr>
</table>
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
