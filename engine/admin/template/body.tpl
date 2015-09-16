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
<a href="{admin-page}" id="logo" data-tooltip="صفحه نخست مدیریت" ></a>
<div id="menu">
<a href="{site-url}" target="_blank" id="menu-icon-home" data-tooltip="مشاهده سایت" ></a>
<a href="{admin-page}&amp;module=counter" id="menu-icon-counter" data-tooltip="آمارگیر" ></a>
<a href="{admin-page}&amp;section=blocks" id="menu-icon-blocks" data-tooltip="بلوک ها" ></a>
<a href="{admin-page}&module=posts&amp;do=posts" id="menu-icon-new-post" data-tooltip="پست ها" ></a>
<a href="{admin-page}&amp;module=account" id="menu-icon-account" data-tooltip="سامانه کاربری" ></a>
<a href="{admin-page}&amp;section=modules" id="menu-icon-modules" data-tooltip="ماژول ها" ></a>
<a href="{admin-page}&amp;section=backup" id="menu-icon-backup" data-tooltip="پشتیبان گیری" ></a>
<a href="{a href='account/logout'}" id="menu-icon-exit" data-tooltip="خروج از سیستم" ></a>
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