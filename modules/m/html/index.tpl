<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fa-IR" dir="rtl">
<head profile="http://gmpg.org/xfn/11">
{head}
<style>
body{background:#EEEEEE;font-family:Tahoma;font-size:9pt;color:Black;padding:0px;margin:10px}
a{text-decoration:none;color:Black}
a:hover{color:Blue}
a:active{color:Red}
img{max-width:99%;border:none}
table#table-posts{width:100%;font-size:9pt;background:#FFFFFF;text-align:center;margin-bottom:10px}
table#table-posts th{background:#0099CC;padding:10px;color:#FFFFFF}
table#table-posts td{padding:10px}
table#table-posts tr.apadana-odd{background:#DFF3FF}
</style>
</head>
<body>
[posts]
<table cellpadding="0" cellspacing="0" id="table-posts">
  <tr>
    <th width="30">#</th>
    <th align="right">عنوان پست</th>
    <th width="100">نویسنده</th>
    <th width="320">تاریخ ارسال</th>
  </tr>
  [for posts]
  <tr class="{odd-even}">
    <td>{id}</td>
    <td align="right"><a href="{url}">{title}</a></td>
    <td>{author}</td>
    <td>{date format="l j F Y ساعت g:i A"}</td>
  </tr>
  [/for posts]
</table>
[for pages]<a href="{url}">[selected]<b>[/selected]&nbsp;{number}&nbsp;[selected]</b>[/selected]</a>[/for pages]
[/posts]
[not-posts]<center>هیچ پستی برای نمایش وجود ندارد!</center>[/not-posts]
</body>
</html>