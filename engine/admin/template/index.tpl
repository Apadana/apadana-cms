
<!-- TAB START-->
<div class="content">
<div class="content-tabs">
<ul>
  <li class="tab-on" id="tab-id-1" onclick="apadana.changeTab(1, 5)">بازدیدها</li>
  <li class="tab-off" id="tab-id-2" onclick="apadana.changeTab(2, 5)">اطلاعات سایت</li>
  <li class="tab-off" id="tab-id-3" onclick="apadana.changeTab(3, 5)">مدیریت سیستم</li>
  <li class="tab-off" id="tab-id-4" onclick="apadana.changeTab(4, 5)">مدیریت محتوای سایت</li>
  <li class="tab-off" id="tab-id-5" onclick="apadana.changeTab(5, 5)">تنظیمات سیستم</li>
</ul>
</div>
<div class="content-main">
<div class="content-space">

<div id="option-id-1" style="display:block">
[counter]
<div style="margin:5px 0px;font-weight:bold">بازدید 10 روز گذشته</div>
<div align="center" dir="ltr">
<script type="text/javascript">
swfobject.embedSWF("{site-url}engine/openChart/open-flash-chart.swf", "line_chart", "840", "550", "9.0.0", "expressInstall.swf",{"data-file":"{chart-url}"});
</script>
<div id="line_chart"></div>
<br>
<span class="break" style="color:white;background:#6495ED">Number=0, {chart-dete-0}: <b>{chart-value-0}</b></span>
<span class="break" style="color:white;background:#6495ED">Number=1, {chart-dete-1}: <b>{chart-value-1}</b></span>
<span class="break" style="color:white;background:#6495ED">Number=2, {chart-dete-2}: <b>{chart-value-2}</b></span>
<span class="break" style="color:white;background:#6495ED">Number=3, {chart-dete-3}: <b>{chart-value-3}</b></span>
<span class="break" style="color:white;background:#6495ED">Number=4, {chart-dete-4}: <b>{chart-value-4}</b></span>
<span class="break" style="color:white;background:#6495ED">Number=5, {chart-dete-5}: <b>{chart-value-5}</b></span>
<span class="break" style="color:white;background:#6495ED">Number=6, {chart-dete-6}: <b>{chart-value-6}</b></span>
<span class="break" style="color:white;background:#6495ED">Number=7, {chart-dete-7}: <b>{chart-value-7}</b></span>
<span class="break" style="color:white;background:#6495ED">Number=8, {chart-dete-8}: <b>{chart-value-8}</b></span>
<span class="break" style="color:white;background:#6495ED">Number=9, {chart-dete-9}: <b>{chart-value-9}</b></span>
</div>
[/counter]
[not-counter]{function name="message" args="متاسفانه شما به بخش آمارگیر سایت برای مشاهده چارتهای آماری دسترسی ندارید!|error"}[/not-counter]
</div>
<!-- /option-id-1 -->
<div id="option-id-2" style="padding-top:5px;display:none">
<table class="apadana-table" cellpadding="0" cellspacing="0">
  <tr class="apadana-odd">
	<th width="180">عنوان</th>
	<th style="border-left:#999999 1px dashed">مقدار</th>
	<th width="180">عنوان</th>
	<th>مقدار</th>
  </tr>
  <tr class="apadana-even">
	<td>تعداد کل کاربران</td>
	<td style="border-left:#999999 1px dashed">{membersCount}</td>
	<td>کاربران دیروز</td>
	<td>{membersYesterday}</td>
  </tr>
  <tr class="apadana-odd">
	<td>کاربران امروز</td>
	<td style="border-left:#999999 1px dashed">{membersToday}</td>
	<td>کاربران این ماه</td>
	<td>{membersMonth}</td>
  </tr>
  <tr class="apadana-even">
	<td>کاربر جدید</td>
	<td style="border-left:#999999 1px dashed">{memberNewName}</td>
	<td>کل پست های منتشر شده</td>
	<td>{postsCount}</td>
  </tr>
  <tr class="apadana-odd">
	<td>کل نظرات</td>
	<td style="border-left:#999999 1px dashed">{commentsCount}</td>
	<td>نظرات تایید نشده</td>
	<td><font color=red><b>{commentsCount2}</b></font></td>
  </tr>
  <tr class="apadana-even">
	<td>تعداد تبادل لینک</td>
	<td style="border-left:#999999 1px dashed">{linksCount}</td>
	<td>تعداد اعضای خبرنامه</td>
	<td>{newsletterCount}</td>
  </tr>
  <tr class="apadana-odd">
	<td>بازدید کل سایت</td>
	<td style="border-left:#999999 1px dashed">{totalCount}</td>
	<td>کل بازدید امسال</td>
	<td>{yearCount}</td>
  </tr>
  <tr class="apadana-even">
	<td>کل بازدید این ماه</td>
	<td style="border-left:#999999 1px dashed">{monthCount}</td>
	<td>بازدیدهای امروز</td>
	<td>{dayCount}</td>
  </tr>
  <tr class="apadana-odd">
	<td>نسخه آپادانا</td>
	<td style="border-left:#999999 1px dashed">{version}</td>
	<td>نمایش خطاها</td>
	<td>{error-reporting}</td>
  </tr>
  <tr class="apadana-even">
	<td>وضعیت سایت</td>
	<td style="border-left:#999999 1px dashed">{offline}</td>
	<td>لینک های سئو</td>
	<td>{rewrite}</td>
  </tr>
  <tr class="apadana-odd">
	<td>ماژول پیشفرض</td>
	<td style="border-left:#999999 1px dashed">{default-module}</td>
	<td>تم سایت</td>
	<td>{theme}</td>
  </tr>
  <tr class="apadana-even">
	<td>ذخیره سازی لینک دهندگان</td>
	<td style="border-left:#999999 1px dashed">{http-referer}</td>
	<td>کلید بخش مدیریت</td>
	<td>{admin}</td>
  </tr>
</table>
</div>
<!-- /option-id-2 -->
<div id="option-id-3" style="display:none">
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
<div id="option-id-4" style="display:none">
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
<div id="option-id-5" style="display:none">
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

<div class="clear"></div>
</div>
</div>
<div class="content-bottom"></div>
</div>
<!-- TAB END-->
