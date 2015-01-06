<script>set_percent(150)</script>
<div class="info">
	<h1>برسی قبل از نصب</h1>
	هر کدام از آیتم های این بخش که قرمز رنگ باشد باید اقدامات لازم برای رفع مشکل آن انجام شود، در غیر اینصورت ممکن است نصب آپادانا با مشکل مواجه شود.
</div>
<table cellpadding="0" cellspacing="0" class="padding">
<tr>
	<td style="width:290px">نسخه PHP بالاتر از 5.3</td>
	<td>{phpversion}</td>
</tr>
<tr>
	<td style="width:290px">پشتیبانی از zlib</td>
	<td>{zlib}</td>
</tr>
<tr>
	<td style="width:290px">پشتیبانی از GD</td>
	<td>{gd}</td>
</tr>
<tr>
	<td style="width:290px">پشتیبانی از MySQL</td>
	<td>{mysql}</td>
</tr>

</table>

<br/>
<div class="info">
	<h1>تنظیمات توصیه شده برای آپادانا</h1>
	توصیه می شود تنظیمات مقابل برای سازگاری کامل با آپادانا بر روی PHP انجام شود.
</div>
<table cellpadding="0" cellspacing="0" class="padding">
<tr>
	<th>نوع</th>
	<th style="width:230px">توصیه شده </th>
	<th style="width:100px">فعلی</th>
</tr>
<tr>
	<td style="width:290px">Safe Mode</td>
	<td>غیرفعال</td>
	<td>{safemode}</td>
</tr>
<tr>
	<td style="width:290px">Display Errors</td>
	<td>غیرفعال</td>
	<td>{display_errors}</td>
</tr>
<tr>
	<td style="width:290px">File Uploads</td>
	<td>فعال</td>
	<td>{file_uploads}</td>
</tr>
<tr>
	<td style="width:290px">Magic Quotes GPC</td>
	<td>غیرفعال</td>
	<td>{magic_quotes_gpc}</td>
</tr>
<tr>
	<td style="width:290px">Magic Quotes Runtime</td>
	<td>غیرفعال</td>
	<td>{magic_quotes_runtime}</td>
</tr>
<tr>
	<td style="width:290px">Register Globals</td>
	<td>غیرفعال</td>
	<td>{register_globals}</td>
</tr>
<tr>
	<td style="width:290px">Output Buffering</td>
	<td>غیرفعال</td>
	<td>{output_buffering}</td>
</tr>
<tr>
	<td style="width:290px">Session Auto Start</td>
	<td>غیرفعال</td>
	<td>{session}</td>
</tr>

</table>

<div class="info">
	<h1>دسترسی فایل ها</h1>
	آپادانا نیاز دارد تا بتواند این فایل ها و پوشه ها را ویرایش کند.
</div>
<table cellpadding="0" cellspacing="0" class="padding">
<tr>
	<td style="width:290px">فایل پیکربندی <font color="#CCCCCC" size="1" dir="ltr">(/engine/config.inc.php)</font></td>
	<td>{config}</td>
</tr>
<tr>
	<td>پوشه کش <font color="#CCCCCC" size="1" dir="ltr">(/engine/cache/)</font></td>
	<td>{cache}</td>
</tr>
<tr>
	<td>پوشه فایل های پشتیبان <font color="#CCCCCC" size="1" dir="ltr">(/engine/admin/backups/)</font></td>
	<td>{backup}</td>
</tr>
<tr>
	<td>پوشه آپلود <font color="#CCCCCC" size="1" dir="ltr">(/uploads/)</font></td>
	<td>{upload}</td>
</tr>
<tr>
	<td>فایل <font color="#CCCCCC" size="1" dir="ltr">(/.htaccess)</font></td>
	<td>{hta}</td>
</tr>
<tr>
	<td>فایل روبات ها <font color="#CCCCCC" size="1" dir="ltr">(/robots.txt)</font></td>
	<td>{robots}</td>
</tr>
</table>

<button onClick="install_load('config')">ادامه نصب آپادانا</button><button onClick="install_load('check')" style="margin-left:10px">برسی مجدد</button>

[ajax]<div class="clear"></div>[/ajax]