[for comments]
<div class="comment {odd-even}">
  <div class="author">
  <div class="pic"><img alt="{author}" src="{member-avatar}" class="photo"></div> 
  <div class="name"><span class="fn">[author-url]<a href="{author-url}" target="_blank" rel="nofollow">[/author-url]{author}[author-url]</a>[/author-url]</span></div> 
  </div> 
  <div class="info">
    <div class="date"> {past-time}</div> <br>

    <div class="act"></div> 
    
  
  <div class="fixed"></div> 
  <div class="content">[not-approve]<font color="red">نظر شما پس از تایید برای عموم نمایش داده خواهد شد!</font><br/>[/not-approve]
{text}
  
[answer]<div class="reply" style="display: block;" align="right"><strong> {answer-author} »  </strong>{answer}
</div>[/answer]
	
  </div>
  </div> 
	<div></div>
</div>
    <div style="margin-bottom:5px;padding-bottom:5px" class="break"></div>
<!--/comment-{id}-->
[/for comments]	
	
	
	
	 

 

<div class="sendcomment">
[post][message]{message}
[/message]<form action="{action}" method="post">
<table cellpadding="5" cellspacing="0">[group=5]
  <tr>
    <td style="width:80px">نام شما</td>
    <td><input name="comment[name]" type="text" value="{name}" size="30"></td>
  </tr>
  <tr>
    <td>ایمیل</td>
    <td><input name="comment[email]" type="text" value="{email}" size="30" style="direction:ltr"> (منتشر نخواهد شد)</td>
  </tr>
  <tr>
    <td>آدرس وبسایت</td>
    <td><input name="comment[url]" type="text" value="{url}" size="30" style="direction:ltr"></td>
  </tr>[/group][editor]
  <tr>
	<td colspan="2">{wysiwyg-textarea}</td>
  </tr>[/editor][not-editor]
  <tr>
	<td>متن نظر</td>
	<td><textarea name="comment[text]" id="comment-text" cols="45" rows="5"></textarea></td>
  </tr>[/not-editor]
  <tr>
    <td style="width:80px">کد امنیتی</td>
    <td><input name="comment[captcha]" type="number" value="" style="width:60px;text-align:center" maxlength="4" style="direction:ltr">&nbsp;{captcha}</td>
  </tr>
  <tr>
    <td></td>
    <td><input type="submit" name="comment[save]" value="ارسال نظر">&nbsp;<input type="reset" value="پاک کردن فرم"></td>
  </tr>
</table>
</form>
[/post][not-post]{message}[/not-post]
 </div>