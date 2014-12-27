<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function post_edit()
{
	$('#div-ajax:visible').slideUp('slow');
	$('#textarea_posts_text').val(CKEDITOR.instances.textarea_posts_text.getData());
	$('#textarea_posts_more').val(CKEDITOR.instances.textarea_posts_more.getData());[for editors]
	$('#{editor-id}').val(CKEDITOR.instances.{editor-id}.getData());[/for editors]
	
	$.ajax({
		type: 'post',
		url: '{admin-page}&module=posts&do=posts-edit&id={id}',
		data: $('#form-edit-posts').serialize(),
		beforeSend: function()
		{
			apadana.loading(1);
		},
		success: function(data)
		{
			$('#div-ajax').slideUp('slow', function(){
				$(this).html(data).slideDown('slow', function(){
					apadana.scroll('#div-ajax');
				});
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
function post_slug(ID, ID2)
{
	text = $(ID).val();
    text = apadana.slug(text);
	
	if (text == '')
	{
		alert('عنوان پست را ننوشته اید!');
		return;
	}
	
	$(ID2).val(text)
}

var tags = new Array();
var tagIndex = 0;
var duplicateTagContorl = new Array();
function post_add_tag()
{
	var newTag = apadana.$('posts-tag').value;

	if (!apadana.trim(newTag))
	{
		alert('فیلد مربوط به برچسب تکمیل نشده است.');
	}
	else
	{
		var newTagArr = newTag.split(',');
		for (var i = 0; i < newTagArr.length; i++)
		{
			var newTagArr2 = newTagArr[i].split('،');

			for (var j = 0; j < newTagArr2.length; j++)
			{
				newTag = apadana.trim(newTagArr2[j]);
				if (newTag)
				{
					if (duplicateTagContorl[newTag])
					{
						alert(newTag+"\nتکراری است.");
					}
					else
					{
						tagIndex++;
						apadana.$('posts-tag').value = '';
						apadana.$('tagBank').innerHTML += "<div id='tag_"+tagIndex+"' onclick='post_delTag("+tagIndex+")'>"+newTag+"</div>";
						tags[tagIndex] = newTag;
						apadana.$('posts-tags').value = tags;
						duplicateTagContorl[newTag]=true;
					}
				}
			}
		}
	}
}
function post_delTag(tagIndex)
{
	duplicateTagContorl[tags[tagIndex]] = false;
	tags[tagIndex] = '-';
	apadana.$('posts-tags').value = tags;
	apadana.$('tag_'+tagIndex).style.display = 'none';
}
function post_handleKeyPress(e)
{
	var key = e.keyCode || e.which;
	if (key == 13)
	{
		post_add_tag();
		return true;
	}
	else
	{
		return false;
	}
}
$(document).ready(function(){
	if (apadana.$('posts-tag').value != '')
	{
		post_add_tag()
	}
})
/*]]>*/
</script>
<style>
#tagBank div{float:right;padding-right:13px;margin-right:10px;height:15px;line-height:10px;background: url({site-url}modules/posts/images/tagdel.gif) right 2px no-repeat;font-size:10px;cursor:pointer;}
#tagBank div:hover{background:url({site-url}modules/posts/images/tagdel.gif) right -18px no-repeat;}
</style>

<div id="div-ajax"></div>
<form id="form-edit-posts" onsubmit="post_edit();return false">

<!-- TAB START-->
<div class="content">
<div class="content-tabs">
<ul>
  <li class="tab-on" id="tab-id-1" onclick="apadana.changeTab(1, 3, function(){$('#div-ajax').slideUp('slow')})">محتوای پست</li>
  <li class="tab-off" id="tab-id-2" onclick="apadana.changeTab(2, 3, function(){$('#div-ajax').slideUp('slow')})">فیلدهای اضافی</li>
  <li class="tab-off" id="tab-id-3" onclick="apadana.changeTab(3, 3, function(){$('#div-ajax').slideUp('slow')})">تنظیمات پست</li>
</ul>
</div>
<div class="content-main">
<div class="content-space">

<div id="option-id-1" style="display:block">
<table cellpadding="6" cellspacing="0">
  <tr>
	<td style="width:90px">عنوان</td>
	<td><input id="posts-title" name="posts[title]" value="{title}" type="text" style="width:681px" lang="fa-ir" /></td>
  </tr>
  <tr>
	<td>نام مستعار</td>
	<td><input id="posts-name" name="posts[name]" value="{name}" type="text" dir="ltr" style="width:700px" />&nbsp;<input type="button" value="بساز" onclick="post_slug('#posts-title', '#posts-name')" /></td>
  </tr>
  <tr>
	<td colspan="2" width="90">{textarea}</td>
  </tr>
  <tr>
	<td colspan="2" width="90">{textarea-more}</td>
  </tr>
  <tr>
	<td colspan="2" width="90"><input type="submit" value="ویرایش پست" /></td>
  </tr>
</table>
</div>
<!-- /option-id-1 -->
<div id="option-id-2" style="display:none">		
[is-fields]
<table cellpadding="6" cellspacing="0">[for fields]
  <tr>
	<td style="width:90px">فیلد {title}[require]&nbsp;<font color="red">*</font>[/require]</td>
	<td>{input}</td>
  </tr>[/for fields]
  <tr>
	<td colspan="2" width="90"><input type="submit" value="ویرایش پست" /></td>
  </tr>
</table>
[/is-fields]
[is-not-fields]{function name="message" args="هیچ فیلد اضافی را هنوز نساخته اید، از بخش فیلدهای اضافی پست ها می توانید فیلد اضافی بسازید.|info"}[/is-not-fields]
</div>
<!-- /option-id-2 -->
<div id="option-id-3" style="display:none">
<table cellpadding="6" cellspacing="0">
  <tr>
	<td style="width:90px">عکس پست</td>
	<td><input id="posts-image" name="posts[image]" value="{image}" type="text" dir="ltr" style="width:675px" />&nbsp;<input type="button" value="رسانه ها" style="width:65px" onclick="apadana.popupWindow('{admin-page}&section=media&noTemplate=true&input=posts-image', 'media', 1000, 700)" /></td>
  </tr>
  <tr>
	<td>موضوعات</td>
	<td>{categories}</td>
  </tr>
  <tr>
	<td>برچسب ها</td>
	<td>
	<input id="posts-tag" type="text" value="{tags}" onkeypress="if (post_handleKeyPress(event)) {return false;} else return true;" /> <input type="button" name="save" value="اضافه کن" onclick="post_add_tag();" />
	<br/><br/>
	<div id="tagBank" class="clear"></div>
	<input name="posts[tags]" id="posts-tags" type="hidden" value="" />
	</td>
  </tr>
  <tr>
	<td></td>
	<td>ساعت سرور سایت: {watch}</td>
  </tr>
  <tr>
	<td>تاریخ پست</td>
	<td>سال{year} &nbsp;&nbsp; ماه{month} &nbsp;&nbsp; روز{day} &nbsp;&nbsp; ساعت:{hour} &nbsp;&nbsp; دقیقه:{minute} &nbsp;&nbsp; ثانیه:{second}</td>
  </tr>
  <tr>
	<td>قابل نمایش برای</td>
	<td>{view} <font color="#BBBBBB" size="1">(فقط ادامه مطلب را محدود می کند)</font></td>
  </tr>
  <tr>
	<td>ارسال نظر</td>
	<td>{comment}</td>
  </tr>
  <tr>
	<td>پست ثابت</td>
	<td>{fixed}</td>
  </tr>
  <tr>
	<td>وضعیت</td>
	<td>{approve}</td>
  </tr>
  <tr>
	<td></td>
	<td><input type="submit" value="ویرایش پست" /></td>
  </tr>
</table>
</div>
<!-- /option-id-3 -->

<div class="clear"></div>
</div>
</div>
<div class="content-bottom"></div>
</div>
<!-- TAB END-->
</form>
