<!-- if !ajax -->
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function robots_edit()
{
    if(confirm("آیا از ویرایش اطمینان دارید؟"))
	apadana.ajax({
		method: 'POST',
        action: '{admin-page}&section=robots&do=edit',
        data: 'robots='+apadana.value('robots-textarea'),
		id: 'robots-ajax'
	})
}
function robots_reset()
{
    if(confirm("آیا از ریست کردن محتوای فایل اطمینان دارید؟"))
    apadana.ajax({
        method: 'get',
        action: '{admin-page}&section=robots&do=edit&reset=true',
        id: 'robots-ajax',
    })
}
/*]]>*/
</script>

<div id="robots-ajax">
[not-writable]{function name="message" args="فایل robots.txt در روت سایت قابل نوشتن نیست.|info"}[/not-writable]
{function name="message" args="آخرین ویرایش در {time} بوده است.|info"}
</div>
<center>
<textarea id="robots-textarea" style="width: 99%; height: 200px; margin-bottom: 5px" dir="ltr">{robots}</textarea>
<button onclick="robots_edit()">ویرایش فایل</button>&nbsp;&nbsp;<button onclick="robots_reset()">بازگردانی محتوای فایل به حالت اولیه</button>
</center>