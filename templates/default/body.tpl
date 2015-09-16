<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fa-IR" dir="rtl">
<head profile="http://gmpg.org/xfn/11">
{head}
<link href="{template}styles/default.css" type="text/css" rel="stylesheet" />
<link href="{template}styles/engine.css" type="text/css" rel="stylesheet" />
</head>
<body>
<div align="center">
<div id="content">
<div id="header">
<div id="info">
<h1>{site-title}</h1>
{site-slogan}<br />
امروز {today}
</div>
<!-- /info -->
<div id="menu">
<a href="{site-url}"><strong>صفحه نخست</strong></a>
<a href="{a href='contact-us'}"><strong>تماس با ما</strong></a>
<a href="{a href='account'}"><strong>سامانه کاربری</strong></a>[group=5]
<a href="{a href='account/register'}"><strong>عضویت</strong></a>[/group][admin]
<a href="{admin-page}"><strong>بخش مدیریت</strong></a>[/admin]
<a href="{a href='rules'}"><strong>قوانین</strong></a>
<a href="{a href='search'}"><strong>جستجو</strong></a>
<a href="{a href='m'}"><strong>نسخه موبایل</strong></a>
</div>
<!-- /menu -->
</div>
<!-- /header -->
<div id="main">
<div id="sidebar-left">
[block-left]{block-left}[/block-left]
</div>
<!-- /sidebar-left -->
<div id="center">
[block-top]{block-top}[/block-top]
{content}

[pagination]
<div class="apadana-pagination"><span>
[for pagination]
[first][off]<a class="off first">&laquo;</a>[/off][on]<a href="{url}" class="first">&laquo;</a>[/on][/first]
[previous][off]<a class="off previous">&lt;</a>[/off][on]<a href="{url}" class="previous">&lt;</a>[/on][/previous]
[page][active]<a class="active">{number}</a>[/active][not-active]<a href="{url}">{number}</a>[/not-active][/page]
[next][off]<a class="off next">&gt;</a>[/off][on]<a href="{url}" class="next">&gt;</a>[/on][/next]
[last][off]<a class="off last">&raquo;</a>[/off][on]<a href="{url}" class="last">&raquo;</a>[/on][/last]
[/for pagination]
</span></div><div class="clear"></div>
[/pagination]

[block-bottom]{block-bottom}[/block-bottom]
</div>
<!-- /center -->
<div id="sidebar-right">
[block-right]{block-right}[/block-right]
</div>
<!-- /sidebar-right -->
<div class="clear"></div>
</div>
<!-- /main -->
<div id="footer">
<span id="left">{copyright}</span>
<span id="right">تعداد ارتباط با دیتابیس: {num-queries} /{memory-get-usage}/ زمان ایجاد صفحه {creation-time} ثانیه</span>
</div><!-- /footer -->
</div>
<!-- /content -->
</div>
</body>
</html>