<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function newsletter_send()
{
    apadana.html('newsletter-ajax', '');
	apadana.value('textarea_newsletter_text', CKEDITOR.instances.textarea_newsletter_text.getData());
	apadana.ajax({
		method: 'post',
		action: '{admin-page}&section=newsletter&do=send',
		data: apadana.serialize('newsletter-form'),
		success: function(data)
		{
			apadana.fadeOut('newsletter-ajax', function(){
				apadana.html('newsletter-ajax', data);
				apadana.fadeIn('newsletter-ajax');
			});
		}
	})
}
/*]]>*/
</script>

<div id="newsletter-ajax"></div>
<form id="newsletter-form">
عنوان خبرنامه:&nbsp;<input name="newsletter[title]" type="text" value="" lang="fa-IR" style="width:70%" /><br/><br/>
{textarea}<br/>
در حال حاظر <font color="green"><b>{members-1}</b></font> کاربر در خبرنامه اختصاصی اعضا عضو هستند و <font color="red"><b>{members-0}</b></font> کاربر مایل به دریافت خبرنامه نیستند.<br/>
<label><input name="newsletter[all]" type="checkbox" value="1" />&nbsp;ارسال خبرنامه برای تمامی کاربران سایت.</label>&nbsp;&nbsp;<font color="red" size="1">(بهتر است در موارد غیر ضروری این گزینه را فعال نکنید.)</font><br/><br/>
<input type="button" value="ارسال خبرنامه" onclick="newsletter_send()" />&nbsp;<input type="reset" value="پاک کردن فرم" onclick="CKEDITOR.instances.textarea_newsletter_text.setData('')" />&nbsp;&nbsp;<font color="red" size="1">(در صورتی که تعداد اعضای خبرنامه زیاد باشد ارسال ایمیل ها چند دقیقه طول خواهد کشید.)</font>
</form>