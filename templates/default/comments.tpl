[for comments]
<div class="block {odd-even}">
<h3 class="title">[author-url]<a href="{author-url}" target="_blank" rel="nofollow">[/author-url]{author}[author-url]</a>[/author-url] در <span title="{date format='l j F Y ساعت g:i A'}">{past-time}</span> گفته</h3>
<div class="main">
<div class="padding">
<img src="{member-avatar}" align="left" />
[not-approve]<font color="red">نظر شما پس از تایید برای عموم نمایش داده خواهد شد!</font><br>[/not-approve]
{text}
[answer]<br /><span style="float:right;background:#AAAAAA;border:#888888 1px dashed;padding:4px;margin-top:3px"><b>{answer-author} گفته:</b><br />{answer}</span>[/answer]
<div class="clear"></div>
</div>
</div>
<div class="bottom"></div>
</div>
<!--/comment-{id}-->
[/for comments]

<div class="block">
<h3 class="title">ارسال یک نظر جدید</h3>
<div class="main">
<div class="padding">
[post][message]{message}
[/message]<form action="{action}" method="post">
<table cellpadding="5" cellspacing="0">[group=5]
  <tr>
    <td style="width:80px">نام شما</td>
    <td><input name="comment[name]" type="text" value="{name}" size="30"></td>
  </tr>
  <tr>
    <td>ایمیل</td>
    <td><input name="comment[email]" type="text" value="{email}" size="30" style="direction: ltr"> (منتشر نخواهد شد)</td>
  </tr>
  <tr>
    <td>آدرس وبسایت</td>
    <td><input name="comment[url]" type="text" value="{url}" size="30" style="direction: ltr"></td>
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
<div class="clear"></div>
</div>
</div>
<div class="bottom"></div>
</div>