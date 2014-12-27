<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function saveOptions()
{
	setAll();	
	$.ajax({
		type: 'post',
		url: '{admin-page}&module=posts&do=options',
		data: $('#form-posts-options').serialize(),
		beforeSend: function()
		{
			$('#div-ajax:visible').slideUp('slow');
			apadana.loading(1);
		},
		success: function(result)
		{
			$('#div-ajax').slideUp('slow', function(){
				$(this).html(result).slideDown('slow');
			});
		},
		error: function()
		{
			alert('در ارتباط خطايي رخ داده است!');
		},
		complete: function()
		{
			apadana.loading(0);
		}
	})
}
function setAll()
{
    if (apadana.$('op-checkbox').checked)
	{
		$('#op-total-category').val($('#op-total-posts').val());
		$('#op-total-tag').val($('#op-total-posts').val());
		$('#op-total-author').val($('#op-total-posts').val());
	}
}
/*]]>*/
</script>

<div id="div-ajax"></div>
<form id="form-posts-options">
<table cellpadding="6" cellspacing="0">
  <tr>
	<td width="200">تعداد پست ها در هر صفحه</td>
	<td><input name="op[total-posts]" id="op-total-posts" value="{posts}" type="text" style="width:30px;text-align:center" onkeyup="setAll()" /></td>
  </tr>
  <tr>
	<td>تعداد پست ها در هر صفحه موضوعات</td>
	<td><input name="op[total-category]" id="op-total-category" value="{category}" type="text" style="width:30px;text-align:center" /></td>
  </tr>
  <tr>
	<td>تعداد پست ها در هر صفحه برچسب ها</td>
	<td><input name="op[total-tag]" id="op-total-tag" value="{tag}" type="text" style="width:30px;text-align:center" /></td>
  </tr>
  <tr>
	<td>تعداد پست ها در هر صفحه نویسندگان</td>
	<td><input name="op[total-author]" id="op-total-author" value="{author}" type="text" style="width:30px;text-align:center" /></td>
  </tr>
  <tr>
	<td colspan="2"><label><input type="checkbox" name="all" value="1" checked="checked" id="op-checkbox" onclick="setAll()" />&nbsp;تعداد پست ها در هر صفحه را برای همه ی گزینه ها تنظیم کن!</label></td>
  </tr>
  <tr>
	<td colspan="2"><input type="submit" value="ذخیره تنظیمات" onclick="saveOptions();return false" /></td>
  </tr>
</table>
</form>