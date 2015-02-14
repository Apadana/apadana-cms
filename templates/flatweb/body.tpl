<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fa-IR" dir="rtl">
<head profile="http://gmpg.org/xfn/11">
{head}
<link href="{template}font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<link href="{template}font-awesome/css/font-awesome.css" rel="stylesheet" />
<link href="{template}styles/default.css" type="text/css" rel="stylesheet" />
<link href="{template}styles/hint.css" type="text/css" rel="stylesheet" />
<link href="{template}styles/fonts.css" type="text/css" rel="stylesheet" />
<link href="{template}styles/engine.css" type="text/css" rel="stylesheet" />
</head>
<body>
<div id="header">
<ul id="navBar">
<li class="current-menu-item"><a href="{site-url}"><span>صفحه نخست</span></a></li>
<li><a href="{a href='contact-us'}"><span>تماس با ما</span></a></li>
<li><a href="{a href='account'}"><span>سامانه کاربري</span></a>
[member]
<ul>
<li><a href="{a href='account/profile-edit'}"><span>ویرایش پروفایل</span></a></li>
<li><a href="{a href='private-messages'}"><span>پیام های خصوصی</span></a></li>
<li><a href="{a href='account/logout'}"><span>خروج</span></a></li>
</ul>
[/member]
[group=5]
<ul>
<li><a href="{a href='account/register'}"><span>عضویت</span></a></li>
<li><a href="{a href='account/login'}"><span>ورود</span></a></li>
<li><a href="{a href='account/forget'}"><span>فراموشی پسورد</span></a></li>
</ul>
[/group]
</li>
<li><a href="{a href='rules'}"><span>قوانين</span></a></li>
<li><a href="{a href='search'}"><span>جستجو</span></a></li>
<li><a href="{a href='m'}"><span>نسخه موبايل</span></a></li>
[admin]<li><a href="{admin-page}"><span>بخش مديريت</span></a></li>[/admin]
<div class="clear"></div>
</ul>
<div class="logo">
<h1><i style="margin-left: 20px;" class="fa fa-code fa-lg"></i><span class="hint--left hint--info" data-hint="{site-slogan}">{site-title}</span></h1> 
    </div>
<div class="clear"></div>
</div>
<div align="center">

<div id="content">

<div id="lside">
[block-left]{block-left}[/block-left]
</div>
<div id="center">
[block-top]{block-top}[/block-top]
{content}
[block-bottom]{block-bottom}[/block-bottom]
[pagination]
<div class="apadana-message-error2">

{templates-list}
<div class="apadana-pagination"><span>
[for pagination]
[first][off]<a class="off first">&laquo;</a>[/off][on]<a class="hint--bottom  hint" data-hint="اولین صفحه" href="{url}" class="first">&laquo;</a>[/on][/first]
[previous][off]<a class="off previous">&lt;</a>[/off][on]<a class="hint--bottom  hint" data-hint="قبلی" href="{url}" class="previous">&lt;</a>[/on][/previous]
[page][active]<a class="active">{number}</a>[/active][not-active]<a class="hint--bottom  hint" data-hint="صفحه {number}" href="{url}">{number}</a>[/not-active][/page]
[next][off]<a class="off next">&gt;</a>[/off][on]<a class="hint--bottom  hint" data-hint="بعدی" href="{url}" class="next">&gt;</a>[/on][/next]
[last][off]<a class="off last">&raquo;</a>[/off][on]<a class="hint--bottom  hint" data-hint="آخرین صفحه" href="{url}" class="last">&raquo;</a>[/on][/last]
[/for pagination]
</span></div><div class="clear"></div>

</div>	[/pagination]
<div class="clear"></div>
</div>

<div class="clear"></div>
</div>
<div style="margin-bottom: 10px;" class="clear"></div>
<div id="footer">
<div class="clear"></div>
<div id="footer-copyright">
<div id="text-right">کليه حقوق براي <a class="hint--top hint--info" data-hint="{site-title}" href="{site-url}" title="{site-title}">{site-title}</a> محفوظ مي باشد.</div>
<div id="text-left">Powered by: <a class="hint--top hint--info" data-hint="سیستم مدیریت محتوای آپادانا" href="http://www.apadanacms.ir">ApadanaCms</a>. Designed by: <a class="hint--top hint--info" data-hint="همیار آپادانا" href="http://www.hamyarap.ir/">Hamyar Apadana</a>.</div>
<div class="clear"></div>
</div>
</div>
<!-- #footer -->


</div>
</body>
</html>