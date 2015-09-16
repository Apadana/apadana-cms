[not-ajax]
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function backup_new()
{
	$.ajax({
		type: 'get',
		url: '{admin-page}&section=backup&do=new',
		beforeSend: function()
		{
			apadana.loading(1);
		},
		success: function(data)
		{
			$('#backup-ajax').slideUp('slow', function(){
				$(this).html(data).slideDown('slow');
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
function backup_restore(file)
{
	if(confirm("با بازگردانی یک فایل پشتیبان کل اطلاعات دستابیس شما حذف خواهد شد، و مجدد از روی این فایل نوشته خواهد شد!\nتوصیه اکید می شود قبل از بازگردانی یک پشتیبان جدید از اطلاعات فعلی ایجاد کنید تا در صورت بروز خطا اطلاعات خود را ازدست ندهید!"))
	$.ajax({
		type: 'get',
		url: '{admin-page}&section=backup&do=restore&file='+file,
		beforeSend: function()
		{
			apadana.loading(1);
		},
		success: function(data)
		{
			$('#backup-ajax').slideUp('slow', function(){
				$(this).html(data).slideDown('slow');
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
function backup_delete(file)
{
    if(confirm('آیا از حذف این فایل پشتیبان اطمینان دارید؟'))
	$.ajax({
		type: 'get',
		url: '{admin-page}&section=backup&do=delete&file='+file,
		beforeSend: function()
		{
			apadana.loading(1);
		},
		success: function(data)
		{
			$('#backup-ajax').slideUp('slow', function(){
				$(this).html(data).slideDown('slow');
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
/*]]>*/
</script>

<div id="backup-ajax">
[/not-ajax]
[backup]
<table class="apadana-table" cellpadding="0" cellspacing="0">
  <tr>
    <th width="50">نام فایل</th>
    <th>زمان ساخت</th>
    <th width="80">حجم فایل</th>
    <th width="5">بازگردانی</th>
    <th width="5">دانلود</th>
    <th width="5">دانلود</th>
    <th width="5">حذف</th>
  </tr>
[for backup]
  <tr class="{odd-even}">
    <td dir="ltr">{file}</td>
    <td>{time}</td>
    <td>{size}</td>
    <td><a href="javascript:backup_restore('{file}')"><img src="{site-url}engine/images/icons/lightning.png" width="16" height="16" data-tooltip="بازگردانی اطلاعات فایل پشتیبان" ></a></td>
    <td><a href="{admin-page}&section=backup&do=download&file={file}&type=gz"><img src="{site-url}engine/images/icons/category.png" width="16" height="16" data-tooltip="دانلود فایل پشتیبان به صورت فشرده" ></a></td>
    <td><a href="{admin-page}&section=backup&do=download&file={file}&type=php"><img src="{site-url}engine/images/icons/page_white_php.png" width="16" height="16" data-tooltip="دانلود فایل پشتیبان با فرمت PHP" ></a></td>
    <td><a href="javascript:backup_delete('{file}')"><img src="{site-url}engine/images/icons/cross-script.png" width="16" height="16" data-tooltip="حذف فایل پشتیبان" ></a></td>
  </tr>
[/for backup]
</table>
[/backup]
[not-backup]{function name="message" args="هیچ فایل پشتیبانی ساخته نشده است!|info"}[/not-backup]
[not-ajax]
</div>
<br/><center><input type="button" value="ایجاد فایل پشتیبان" onclick="backup_new()" /></center>
[/not-ajax]