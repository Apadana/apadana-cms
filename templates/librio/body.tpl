<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"><head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	{head}
<link href="{template}styles/default.css" type="text/css" rel="stylesheet" />
<link href="{template}styles/engine.css" type="text/css" rel="stylesheet" />
<!--[if IE]><link rel="stylesheet" type="text/css" href="{template}styles/ie.css" media="screen" /><![endif]-->
	 
</head>

<body>

<div id="container">

	<div id="header">
		<h1><a href="{site-url}">{site-title}</a><span>{site-slogan}</span></h1>
		<div id="search">
	 	
			<form method="get"  action="{site-url}search/result">
				<input value="جستجو" id="q" onkeyup="lookup(this.value);" name="story" onfocus="if(this.value=='جستجو')this.value=''" onblur="if(this.value=='')this.value='جستجو'" type="text">
				<input name="all-modules" value="1" type="hidden">	<div id="suggestions"></div>
			</form>
		</div>
	</div>

	<div id="nav">
		<ul>
<li class="page_item"><a href="{site-url}"><strong>صفحه نخست</strong></a></li>
<li class="page_item"><a href="{a href='contact-us'}"><strong>تماس با ما</strong></a></li>
<li class="page_item"><a href="{a href='account'}"><strong>سامانه کاربری</strong></a></li>[group=5]
<li class="page_item"><a href="{a href='account/register'}"><strong>عضویت</strong></a></li>[/group][admin]
<li class="page_item"><a href="{admin-page}"><strong>بخش مدیریت</strong></a></li>[/admin]
<li class="page_item"><a href="{a href='rules'}"><strong>قوانین</strong></a></li>
<li class="page_item"><a href="{a href='search'}"><strong>جستجو</strong></a></li>
<li class="page_item"><a href="{a href='m'}"><strong>نسخه موبایل</strong></a></li>
		</ul>
		<a href="{site-url}" id="feed">آر اس اس</a>
	</div>
	
	<div id="wrapper">	
	
			 
<div id="content">

	[block-top]{block-top}[/block-top]
{content}
[block-bottom]{block-bottom}[/block-bottom]
		 


[pagination] 
		<div id="pages">
[for pagination]
[first][off]<a class="off first">&laquo;</a>[/off][on]<a href="{url}" class="first">&laquo;</a>[/on][/first]
[previous][off]<a class="off previous">&lt;</a>[/off][on]<a href="{url}" class="previous">&lt;</a>[/on][/previous]
[page][active]<a class="active">{number}</a>[/active][not-active]<a href="{url}">{number}</a>[/not-active][/page]
[next][off]<a class="off next">&gt;</a>[/off][on]<a href="{url}" class="next">&gt;</a>[/on][/next]
[last][off]<a class="off last">&raquo;</a>[/off][on]<a href="{url}" class="last">&raquo;</a>[/on][/last]
[/for pagination]
		</div>
		[/pagination]

		
</div>
		
<div id="sidebar">

[block-left]{block-left}[/block-left] 
[block-right]{block-right}[/block-right]
		
</div>	



</div>
	
	<div id="footer">
		<div id="footer_right">
		تعداد ارتباط با دیتابیس: {num-queries} / زمان ایجاد صفحه {creation-time} ثانیه</div>
			<div id="footer_left">
			Theme : <a rel="nofollow" href="mailto:kazem1368@gmail.com">FanoosTheme</a> {copyright}</div>
		
	</div>
</div>
 
		</body></html>