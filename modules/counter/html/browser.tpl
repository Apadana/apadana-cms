[version]<table class="apadana-table module-counter" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th style="width:100px">نام مرورگر</th>
	<th style="width:20px">نسخه</th>
	<th>نمودار</th>
	<th style="width:20px">تعداد</th>
</tr>
</thead>
<tbody>[for browser]
<tr class="{odd-even}">
	<td dir="ltr">{name}</td>
	<td dir="ltr">{version}</td>
	<td>[all]این رکورد تعداد کل را نشان می دهد.[/all][not-all]<div class="progress progress-{progress}"><span style="width: {percent}%;"><b>{percent}%</b></span></div>[/not-all]</td>
	<td>{count}</td>
</tr>[/for browser]
</tbody>
</table>[/version]
[not-version]<table class="apadana-table module-counter" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th style="width:20px">عکس</th>
	<th style="width:100px">نام مرورگر</th>
	<th>نمودار</th>
	<th style="width:20px">تعداد</th>
</tr>
</thead>
<tbody>[for browser]
<tr class="{odd-even}">
	<td><img src="modules/counter/images/browsers/{icon}" alt="browser {name}" /></td>
	<td dir="ltr"><a href="{a href='counter/browser/{name}'}">{name}</a></td>
	<td><div class="progress progress-{progress}"><span style="width: {percent}%;"><b>{percent}%</b></span></div></td>
	<td>{count}</td>
</tr>[/for browser]
</tbody>
</table>[/not-version]