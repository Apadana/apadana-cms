[for comments]
[approve]
<div class="apadana-message-ta">
<a href="{author-url}" title="{author}">
<img src="{member-avatar}" id="apadana-av" />
</a>
<span><strong>{author} ميگه: </strong></span>
<br>
{text}

[answer]
<br>
<span style="color: #000;">------------------------------------------------------------------------------------</span><br />
<img src="{site-url}engine/images/no-avatar.png" id="apadana-av" />
<b>مدير کل سايت گفته:</b><br />{answer}

[/answer]
<div class="clear"></div>
</span>
</div>

[/approve]

[not-approve]
<div class="apadana-message-nta"><div class="lg">در انتظار تایید</div>
<a href="{author-url}" title="{author}">
<img src="{member-avatar}" id="apadana-av2" />
</a>
<span><strong>{author} ميگه: </strong></span>
<br>
{text}
<div class="clear"></div>
</span>

</div>

[/not-approve]
<!--/comment-{id}-->
[/for comments]

<div class="block">
<h4><i class="fa fa-comments-o fa-lg"></i> ارسال دیدگاه تازه</h4>
<div class="space">
[post][message]{message}
[/message]<form action="{action}" method="post">
<table cellpadding="5" cellspacing="0">[group=5]
  <tr>
    <td style="width:80px">نام شما</td>
    <td><input name="comment[name]" type="text" value="{name}" size="30"></td>
  </tr>
  <tr>
    <td>ايميل</td>
    <td><input name="comment[email]" type="text" value="{email}" size="30" style="direction: ltr"> (منتشر نخواهد شد)</td>
  </tr>
  <tr>
    <td>آدرس وبسايت</td>
    <td><input name="comment[url]" type="text" value="{url}" size="30" style="direction: ltr"></td>
  </tr>[/group][editor]
  <tr>
	<td colspan="2">{wysiwyg-textarea}</td>
  </tr>[/editor][not-editor]
  <tr>
	<td>متن دیدگاه</td>
	<td><textarea name="comment[text]" id="comment-text" cols="45" rows="5"></textarea></td>
  </tr>[/not-editor]
  <tr>
    <td style="width:80px">کد امنيتي</td>
    <td><input name="comment[captcha]" type="number" value="" style="width:60px;text-align:center" maxlength="4" style="direction:ltr">&nbsp;{captcha}</td>
  </tr>
  <tr>
    <td></td>
    <td><input type="submit" name="comment[save]" value="ارسال دیدگاه">&nbsp;<input type="reset" value="پاک کردن فرم"></td>
  </tr>
</table>
</form>
[/post][not-post]{message}[/not-post]
<div class="clear"></div>
</div>
</div>