[intro]
<script>
	apadana.onloadArray.push("apadana.infoBox('#intro',1,0,'intro');");	
</script>
<script type="text/javascript" src="{site-url}engine/admin/template/javascript/intro.min.js"></script>
<link href="{site-url}engine/admin/template/styles/introjs.min.css" type="text/css" rel="stylesheet" />
<div id = "intro" style="display:none;">
از اینکه از آپادانا استفاده میکنید خیلی خوشحالیم.<br/>
به نظر میرسد این اولین بازدید شما از آپادانا است برای همین برای شما یک آموزش کوچک ترتیب داده ایم.<br/>
اگر میخواهید آموزش را ببینید لطفا روی دکمه زیر کلیک کنید واگرنه می توانید این صفحه را ببندید.<br/>
<button onclick="apadana.infoBox(null,0);startintro();">دیدن آموزش</button>&nbsp;&nbsp;&nbsp;<button onclick="apadana.infoBox(null,0);">بستن این صفحه</button>
</div>
[/intro]
<!-- TAB START-->
<div class="content">
<div class="content-tabs">
<ul>
  <li class="tab-on"  id="tab-id-1" onclick="apadana.changeTab(1, 5)">اطلاعات</li>
  <li class="tab-off" id="tab-id-2" onclick="apadana.changeTab(2, 5)">مدیریت سیستم</li>
  <li class="tab-off" id="tab-id-3" onclick="apadana.changeTab(3, 5)">مدیریت محتوای سایت</li>
  <li class="tab-off" id="tab-id-4" onclick="apadana.changeTab(4, 5)">تنظیمات سیستم</li>
  <li class="tab-off" id="tab-id-5" onclick="apadana.changeTab(5, 5)">دفترچه یادداشت</li>
</ul>
</div>
<div class="content-main">
<div class="content-space">

<!-- /option-id-1 -->
<div id="option-id-1" style="padding-top:5px;">

<span class="label" style="margin-top:-5px;">اطلاعات سایت</span><br/>

<table class="apadana-table" cellpadding="0" cellspacing="0">
  <tr>
	<th width="180">عنوان</th>
	<th width="180" style="border-left:#999999 1px dashed">مقدار</th>
	<th width="180">عنوان</th>
	<th width="180">مقدار</th>
  </tr>
  <tr>
	<td>تعداد کل کاربران</td>
	<td style="border-left:#999999 1px dashed">{membersCount}</td>
	<td>کاربران دیروز</td>
	<td>{membersYesterday}</td>
  </tr>
  <tr>
	<td>کاربران امروز</td>
	<td style="border-left:#999999 1px dashed">{membersToday}</td>
	<td>کاربران این ماه</td>
	<td>{membersMonth}</td>
  </tr>
  <tr>
	<td>کاربر جدید</td>
	<td style="border-left:#999999 1px dashed">{memberNewName}</td>
	<td>کل پست های منتشر شده</td>
	<td>{postsCount}</td>
  </tr>
  <tr>
	<td>کل نظرات</td>
	<td style="border-left:#999999 1px dashed">{commentsCount}</td>
	<td>نظرات تایید نشده</td>
	<td><font color=red><b>{commentsCount2}</b></font></td>
  </tr>
  <tr>
	<td>نسخه آپادانا</td>
	<td style="border-left:#999999 1px dashed">{version}</td>
	<td>نمایش خطاها</td>
	<td>{error-reporting}</td>
  </tr>
  <tr>
	<td>وضعیت سایت</td>
	<td style="border-left:#999999 1px dashed">{offline}</td>
	<td>لینک های سئو</td>
	<td>{rewrite}</td>
  </tr>
  <tr>
	<td>ماژول پیشفرض</td>
	<td style="border-left:#999999 1px dashed">{default-module}</td>
	<td>تم سایت</td>
	<td>{theme}</td>
  </tr>
  <tr>
	<td>ذخیره سازی لینک دهندگان</td>
	<td style="border-left:#999999 1px dashed">{http-referer}</td>
	<td>کلید بخش مدیریت</td>
	<td>{admin}</td>
  </tr>
</table>
<span class="label" style="margin-top:13px;" onmouseover="tooltip.show('اطلاعات سرور روزانه آپدیت می شوند مگر اینکه کش را پاک کنید')" onmouseout="tooltip.hide()">اطلاعات سرور</span>

<table class="apadana-table" cellpadding="0" cellspacing="0">
  <tr>
	<th width="180">عنوان</th>
	<th width="180" style="border-left:#999999 1px dashed">مقدار</th>
	<th width="180">عنوان</th>
	<th width="180">مقدار</th>
  </tr>
  <tr>
	<td>سیستم عامل</td>
	<td style="border-left:#999999 1px dashed">{server-os}</td>
	<td>نگارش PHP</td>
	<td>{server-php}</td>
  </tr> 
  <tr>
	<td>نگارش MySQL</td>
	<td style="border-left:#999999 1px dashed">{server-mysql}</td>
	<td>MySQL Improved</td>
	<td>{server-mysqli}</td>
  </tr>
  <tr>
	<td>حجم فایل های Cache</td>
	<td style="border-left:#999999 1px dashed"><span dir=ltr>{server-cache-space}</span></td>
	<td>حجم پایگاه داده</td>
	<td><span dir=ltr>{server-mysql-space}</span></td>
  </tr>
  <tr>
	<td>حجم مجاز استفاده از رم</td>
	<td style="border-left:#999999 1px dashed"><span dir=ltr>{server-memory-limit}</span></td>
	<td onmouseover="tooltip.show('اگه می خواهید فایلی(یا تصویری) را روی سرور آپلود کنید سرور اجازه نمی دهد حجم آن فایل بیشتر از مقدار مجاز باشد')" onmouseout="tooltip.hide()">حجم مجاز آپلود فایل</td>
	<td><span dir=ltr>{server-upload-limit}</span></td>
  </tr>
  <tr>
	<td>فضای خالی هاست</td>
	<td style="border-left:#999999 1px dashed"><span dir=ltr>{server-free}</span></td>
	<td>افزونه mod_rewrite</td>
	<td><span dir=ltr>{server-rewrite}</span></td>
  </tr>
  <tr>
	<td>افزونه zlib</td>
	<td style="border-left:#999999 1px dashed">{server-zlib}</td>
	<td>افزونه GD</td>
	<td>{server-gd}</td>
  </tr> 
 </table>
</div>
<!-- /option-id-2 -->
<div id="option-id-2" style="display:none">
<table id="apadana-admin-index" cellpadding="10" cellspacing="0" style="text-align:center">
	<tr>
	[for index]
	<td><a href="{link}"><img src="{image}" width="80" height="80"><br>{title}</a></td>
	[tr]</tr><tr>[/tr]
	[/for index]
	</tr>
</table>
</div>
<!-- /option-id-3 -->
<div id="option-id-3" style="display:none">
<table id="apadana-admin-content" cellpadding="10" cellspacing="0" style="text-align:center">
	<tr>
	[for content]
	<td><a href="{link}"><img src="{image}" width="80" height="80"><br>{title}</a></td>
	[tr]</tr><tr>[/tr]
	[/for content]
	</tr>
</table>
</div>
<!-- /option-id-4 -->
<div id="option-id-4" style="display:none">
<table id="apadana-admin-options" cellpadding="10" cellspacing="0" style="text-align:center">
	<tr>
	[for options]
	<td><a href="{link}"><img src="{image}" width="80" height="80"><br>{title}</a></td>
	[tr]</tr><tr>[/tr]
	[/for options]
	</tr>
</table>
</div>
<!-- /option-id-5 -->
<div id="option-id-5" style="display:none">
	در این جا می توانید یادداشت های خود را بنویسید. در نظر داشته باشد که هر چه اینجا می نویسید دیگر مدیران هم می بینند یعنی علاوه بر اینکه می توانید یادداشت های خود را اینجا بنویسید می توانید برای دیگر مدیران نیز پیام بگذارید.<br/><br/>
	<textarea id="admin-note" cols="119" rows="20" dir="rtl" >{admin-note}</textarea><br/><br/>
	<button onclick="update_admin_note();">ارسال</button><span id="note-msg"></span>
</div>
<script type="text/javascript">
	function update_admin_note () {

		admin_note = $('#admin-note').val();
	
		$.ajax({
			type: 'post',
			url: '{admin-page}&section=admin_note',
			data: { note : admin_note } ,
			beforeSend: function()
			{
				$('#note-msg').html('<font color="orange">&nbsp;&nbsp;&nbsp;درحال ارسال</font>');
			},
			success: function(data)
			{
				$('#note-msg').html(data);
			},
			error: function()
			{
				$('#note-msg').html('<font color="red">&nbsp;&nbsp;&nbsp;متاسفانه انجام نشد</font>');
			}
		})
	}
</script>
<div class="clear"></div>
</div>
</div>
</div>
<!-- TAB END-->
