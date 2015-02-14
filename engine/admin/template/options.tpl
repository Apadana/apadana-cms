<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function saveOptions()
{
	$('#textarea_options_offline_message').val( CKEDITOR.instances.textarea_options_offline_message.getData() );
	$('#textarea_options_rules').val( CKEDITOR.instances.textarea_options_rules.getData() );
	$.ajax({
		type: 'post',
		url: '{admin-page}&section=options&do=save',
		data: $('#form-options').serialize(),
		beforeSend: function()
		{
			closexAjaxDiv()
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
function closexAjaxDiv()
{
	$('#div-ajax:visible').slideUp('slow');
}
/*]]>*/
</script>

<div id="div-ajax"></div>
<form id="form-options" onsubmit="saveOptions();return false">

<!-- TAB START-->
<div class="content">
<div class="content-tabs">
<ul>
  <li class="tab-on" id="tab-id-1" onclick="apadana.changeTab(1, 6, closexAjaxDiv)">تنظیمات عمومی</li>
  <li class="tab-off" id="tab-id-2" onclick="apadana.changeTab(2, 6, closexAjaxDiv)">تنظیمات سئو</li>
  <li class="tab-off" id="tab-id-3" onclick="apadana.changeTab(3, 6, closexAjaxDiv)">تنظیمات نظرات</li>
  <li class="tab-off" id="tab-id-4" onclick="apadana.changeTab(4, 6, closexAjaxDiv)">غیرفعال کردن سایت</li>
  <li class="tab-off" id="tab-id-5" onclick="apadana.changeTab(5, 6, closexAjaxDiv)">قوانین سایت</li>
  <li class="tab-off" id="tab-id-6" onclick="apadana.changeTab(6, 6, closexAjaxDiv)">تنظیمات ایمیل</li>
</ul>
</div>
<div class="content-main">
<div class="content-space">

<div id="option-id-1" style="display:block">
<table cellspacing="5">
  <tr>
	<td width="140">کلید بخش مدیریت</td>
	<td><input name="options[admin]" type="text" value="{admin-key}" style="width:100px" dir="ltr" /> <font color="#BBBBBB" size="1" dir="ltr">{site-url}?admin=</font></td>
  </tr>
  <tr>
	<td>ماژول پیشفرض سایت</td>
	<td>{default-module}</td>
  </tr>
  <tr>
	<td>ایمیل سایت</td>
	<td><input name="options[mail]" type="text" value="{mail}" style="width:200px" dir="ltr" /> <font color="#BBBBBB" size="1">در ایمیل های ارسالی از سایت استفاده می شود.</font></td>
  </tr>
  <tr>
	<td>تعداد مطالب در خوراک</td>
	<td><input name="options[feed-limit]" type="text" value="{feed-limit}" style="width:20px" dir="ltr" /> <font color="#BBBBBB" size="1">تعداد مطالبی که در آر اس اس نمایش داده می شود را مشخص می کند.</font></td>
  </tr>
  <tr>
	<td>ضد سیل</td>
	<td><label><input type="checkbox" name="options[antiflood]" value="1"[anti-flood] checked="checked"[/anti-flood]  />&nbsp;سامانه Anti flood برای مقابله با حملات DDoS فعال باشد.</label></td>
  </tr>
  <tr>
	<td>لینک دهندگان</td>
	<td><label><input type="checkbox" name="options[http-referer]" value="1"[referer] checked="checked"[/referer]  />&nbsp;آدرس سایت های لینک دهنده را ثبت کن.</label></td>
  </tr>
  <tr>
	<td>لینک ها</td>
	<td><label><input type="checkbox" name="options[replace-link]" value="1"[replace] checked="checked"[/replace]  />&nbsp;کاربران غیر عضو به لینک های موجود در پست ها و سایر محتوا ها دسترسی نداشته باشند.</label></td>
  </tr>
   <tr>
  	<td>اجازه تغیر قالب</td>
  	<td><input name="options[allow-change-theme]" type="checkbox"  value="1"[change-theme] checked="checked"[/change-theme] />&nbsp;کاربر اجازه دارد از بین قالب های نصب شده یکی را به دلخواه انتخاب کند</td>
   </tr>
  <tr>
	<td>رنگ ادیتور</td>
	<td><input name="options[editor-color]" type="text" style="width:45px;text-align:center" value="{editor-color}" class="color" dir="ltr" /></td>
  </tr>
  <tr>
	<td colspan="2"><input type="submit" value="ذخیره تنظیمات" onclick="saveOptions();return false" /></td>
  </tr>
</table>
</div>
<!-- /option-id-1 -->
<div id="option-id-2" style="display:none">
<table cellspacing="5">
  <tr>
	<td width="150">عنوان سایت</td>
	<td><input name="options[title]" type="text" value="{site-title}" lang="fa-IR" style="width:70%" /></td>
  </tr>
  <tr>
	<td>شعار سایت</td>
	<td><textarea name="options[slogan]" style="width:95%;height:100px">{site-slogan}</textarea></td>
  </tr>
  <tr>
	<td>توضیحات صفحات</td>
	<td><textarea name="options[meta-desc]" style="width:95%;height:100px">{description}</textarea></td>
  </tr>
  <tr>
	<td>کلمات کلیدی صفحات</td>
	<td><textarea name="options[meta-keys]" style="width:95%;height:100px">{keywords}</textarea></td>
  </tr>
  <tr>
	<td>سئو</td>
	<td><label><input type="checkbox" name="options[rewrite]" value="1"[rewrite-on] checked="checked"[/rewrite-on]  />&nbsp;لینک های سئو فعال باشد.</label></td>
  </tr>
  <tr>
	<td>جداکننده لینک ها</td>
	<td><input name="options[separator-rewrite]" type="text" value="{separator-rewrite}" style="width:20px;text-align:center" dir="ltr" /> <font color="#BBBBBB" size="1">بخش های لینک های سئو توسط آن از هم جدا می شوند.</font> <font color="red" size="1">(هرگز از "<b>-</b>" استفاده نکنید!)</font></td>
  </tr>
  <tr>
	<td>پسوند لینک ها</td>
	<td><input name="options[file-rewrite]" type="text" value="{file-rewrite}" style="width:40px;text-align:center" dir="ltr" /> <font color="#BBBBBB" size="1">پسوند لینک های سئو را مشخص می کند، می توانید آن را خالی رها کنید.</font></td>
  </tr>
  <tr>
	<td>اصلاح آدرس</td>
	<td><label><input type="checkbox" name="options[url-correction]" value="1"[correction] checked="checked"[/correction] />&nbsp;به طور مثال در صورتی که آدرس سایت شما www.example.ir باشد و کاربر به آدرس example.ir مراجعه کند سیستم به صورت خودکار او را به آدرس اصلی انتقال دهد.</label></td>
  </tr>
  <tr>
	<td colspan="2"><input type="submit" value="ذخیره تنظیمات" onclick="saveOptions();return false" /></td>
  </tr>
</table>
</div>
<!-- /option-id-2 -->
<div id="option-id-3" style="display:none">
<table cellspacing="5">
  <tr>
	<td width="80">حروف</td>
	<td>حداکثر تعداد حروف نظرات: <input name="options[comments][limit]" type="text" value="{comment-limit}" style="width:25px;text-align:center" /></td>
  </tr>
  <tr>
	<td>مهمان</td>
	<td><label><input type="checkbox" name="options[comments][post-guest]" value="1"[comment-guest] checked="checked"[/comment-guest] />&nbsp;کاربران مهمان اجازه ارسال نظر را داشته باشند.</label></td>
  </tr>
  <tr>
	<td>ادیتور</td>
	<td><label><input type="checkbox" name="options[comments][editor]" value="1"[comment-editor] checked="checked"[/comment-editor] />&nbsp;ادیتور متن برای بخش نظرات فعال باشد.</label></td>
  </tr>
  <tr>
	<td>ایمیل</td>
	<td><label><input type="checkbox" name="options[comments][email]" value="1"[comment-email] checked="checked"[/comment-email] />&nbsp;درج ایمیل برای ارسال نظر اجباری باشد.</label></td>
  </tr>
  <tr>
	<td>تایید</td>
	<td><label><input type="checkbox" name="options[comments][approve]" value="1"[comment-approve] checked="checked"[/comment-approve] />&nbsp;نظرات پس از تایید مدیران سایت نمایش داده شود.</label></td>
  </tr>

  <tr>
  	<td>صفحه بندی</td>
  	<td>
		<label><input type="checkbox" name="options[comments][pagination]" value="1" [comment-pagi] checked="checked" [/comment-pagi] />&nbsp;صفحه بندی نظرات فعال باشد.&nbsp;<font size="1" color="#BBB">اگر صفحه بندی فعال باشد نظرات هر مطلب صفحه بندی شده و تمام نظرات به صورت یکجا در زیر مطلب نمایش داده نمی شود</font>
		</label>
  	</td>
  </tr>

    <tr>
    	<td>تعداد</td>
    	<td>
	  		 حداکثر تعداد نظرات در هر صفحه <input style="width:30px;text-align:center;" type="text" value="{comments-per-page}" name="options[comments][per-page]" />  باشد.
    	</td>
    </tr>

  <tr>
	<td colspan="2"><input type="submit" value="ذخیره تنظیمات" onclick="saveOptions();return false" /></td>
  </tr>
</table>
</div>
<!-- /option-id-3 -->
<div id="option-id-4" style="display:none">
{offline-message}
غیرفعال کردن سایت: <label><input type="radio" name="options[offline]" value="1"[offline-on] checked="checked"[/offline-on] />بله</label> <label><input type="radio" name="options[offline]" value="0"[offline-off] checked="checked"[/offline-off] />خیر</label><br><br>
<input type="submit" value="ذخیره تنظیمات" onclick="saveOptions();return false" />
</div>
<!-- /option-id-4 -->
<div id="option-id-5" style="display:none">
{rules}<br/>
<input type="submit" value="ذخیره تنظیمات" onclick="saveOptions();return false" />
</div>
<!-- /option-id-5 -->
<div id="option-id-6" style="display:none">
<table cellspacing="5">
  <tr>
	<td width="80">هاست</td>
	<td><input name="options[smtp-host]" type="text" value="{smtp-host}" style="width:300px" dir="ltr" /> <font color="#BBBBBB" size="1">مثال: smtp.example.ir</font></td>
  </tr>
  <tr>
	<td>نام کاربری</td>
	<td><input name="options[smtp-username]" type="text" value="{smtp-username}" style="width:300px" dir="ltr" /> <font color="#BBBBBB" size="1">مثال: name@example.ir</font></td>
  </tr>
  <tr>
	<td>پسورد</td>
	<td><input name="options[smtp-password]" type="password" value="{smtp-password}" style="width:300px" dir="ltr" /></td>
  </tr>
  <tr>
	<td>پورت</td>
	<td><input name="options[smtp-port]" type="text" value="{smtp-port}" style="width:30px;text-align:center" dir="ltr" /></td>
  </tr>
  <tr>
	<td></td>
	<td>در صورتی که مایل به استفاده از smtp نیستید فیلدهای آن را خالی رها کنید.</td>
  </tr>
  <tr>
	<td colspan="2"><input type="submit" value="ذخیره تنظیمات" onclick="saveOptions();return false" /></td>
  </tr>
</table>
</div>
<!-- /option-id-6 -->
<div class="clear"></div>
</div>
</div>
<div class="content-bottom"></div>
</div>
<!-- TAB END-->
</form>
