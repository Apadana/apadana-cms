<script type="text/javascript">
var fileEdit = '';
function templates_load()
{
	fileEdit = apadana.value('template-file');
	apadana.ajax({
        type: 'POST',
        action: '{admin-page}&section=templates&do=edit&name={template}',
        data: 'file='+fileEdit,
        success: function(data)
        {
            if(data == '{error}')
            	alert('فایل انتخابی شما معتبر نیست!');
            else
			{
    			apadana.html('template-file-name', fileEdit);
    			apadana.html('template-button', 'ویرایش فایل '+fileEdit);
    			apadana.value('template-contents', data);
    			apadana.showID('template-form');
			}
        }
    });
}
function templates_new()
{
	apadana.ajax({
        type: 'POST',
        action: '{admin-page}&section=templates&do=edit&name={template}',
        data: 'new='+apadana.value('template-file-new'),
        success: function(data)
        {
            data = apadana.trim(data);
            if(data == 'OK')
				apadana.location("{admin-page}&section=templates&do=edit&name={template}");
			else
				alert(data);
        }
    });
}
function templates_edit()
{
	if(fileEdit == '')
	{
		return alert('ابتدا یک فایل را برای ویرایش انتخاب کنید');
	}

	apadana.ajax({
        type: 'POST',
        action: '{admin-page}&section=templates&do=edit&name={template}',
        data: 'file='+fileEdit+'&contents='+encodeURIComponent(apadana.value('template-contents')),
        success: function(data)
        {
            data = apadana.trim(data);
			if(data == '{error}')
			{
            	alert('فایل انتخابی شما معتبر نیست!');
			}
            else if(data != '')
			{
				alert(data);
			}
        }
    });
}
</script>

<div align="center" style="margin:10px 0px">
<select style="width:400px;margin-bottom:10px" dir="ltr" id="template-file">[for files]<option value="{file}">{file}</option>[/for files]</select>
<button onclick="templates_load()">ویرایش فایل</button>
</div>
<div align="center" style="margin:10px 0px">
<input style="width:330px;margin-bottom:10px" dir="ltr" id="template-file-new" type="text">
<button onclick="templates_new()">ساختن فایل یا پوشه جدید</button>
</div>

<div id="template-form" style="display:none">
<p style="margin: 10px 0px">در حال ویرایش فایل <b dir="ltr" id="template-file-name"></b>:</p>
<textarea name="g" dir="ltr" id="template-contents" style="width:99%;height:500px"></textarea>
<center><button onclick="templates_edit()" id="template-button" style="margin:5px 0px">ویرایش فایل</button></center>
</div>
