<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fa-ir" dir="rtl">
<head profile="http://gmpg.org/xfn/11">
{head}
<script type="text/javascript" src="{site-url}engine/javascript/tooltip.js"></script>
<script type="text/javascript" src="{site-url}engine/javascript/farsiType.js"></script>
<script type="text/javascript" src="{site-url}engine/admin/template/javascript/admin.js"></script>
<link href="{site-url}engine/admin/template/styles/default.css" type="text/css" rel="stylesheet" />
<link href="{site-url}engine/admin/template/styles/engine.css" type="text/css" rel="stylesheet" />
</head>
<body>
<div align="center">
<div id="wrap">
<div id="header">
<a href="{admin-page}" id="logo" onmouseover="tooltip.show('صفحه نخست مدیریت')" onmouseout="tooltip.hide()"></a>
<div id="menu">
<a href="{site-url}" target="_blank" id="menu-icon-home" onmouseover="tooltip.show('مشاهده سایت')" onmouseout="tooltip.hide()"></a>
<a href="{admin-page}&amp;module=counter" id="menu-icon-counter" onmouseover="tooltip.show('آمارگیر')" onmouseout="tooltip.hide()"></a>
<a href="{admin-page}&amp;section=blocks" id="menu-icon-blocks" onmouseover="tooltip.show('بلوک ها')" onmouseout="tooltip.hide()"></a>
<a href="{admin-page}&module=posts&amp;do=posts" id="menu-icon-new-post" onmouseover="tooltip.show('پست ها')" onmouseout="tooltip.hide()"></a>
<a href="{admin-page}&amp;module=account" id="menu-icon-account" onmouseover="tooltip.show('سامانه کاربری')" onmouseout="tooltip.hide()"></a>
<a href="{admin-page}&amp;section=modules" id="menu-icon-modules" onmouseover="tooltip.show('ماژول ها')" onmouseout="tooltip.hide()"></a>
<a href="{admin-page}&amp;section=backup" id="menu-icon-backup" onmouseover="tooltip.show('پشتیبان گیری')" onmouseout="tooltip.hide()"></a>
<a href="{a href='account/logout'}" id="menu-icon-exit" onmouseover="tooltip.show('خروج از سیستم')" onmouseout="tooltip.hide()"></a>
</div>
<div class="clear"></div>
</div>
<!-- #header -->

{content}

<div id="footer">
<span id="left">Powered by <a href="http://www.apadanacms.ir/?ref=admin" target="_blank" rel="copyright">Apadana Cms</a> copyright &copy; {function name="date" args="Y"}</span>
<span id="right">تعداد ارتباط با دیتابیس: {num-queries} / حافظه درگیر سیستم: {memory-get-usage} / زمان ایجاد صفحه {creation-time} ثانیه</span>
<div class="clear"></div>
</div>
<!-- #footer -->
</div>
<!-- #wrap -->
</div>
</body>
</html>