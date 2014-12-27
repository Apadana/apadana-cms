<table class="apadana-table module-counter" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th style="width:20px">شماره</th>
	<th style="width:100px">سال</th>
	<th>نمودار</th>
	<th style="width:20px">تعداد</th>
</tr>
</thead>
<tbody>[for month]
<tr class="{odd-even}">
	<td>{number}</td>
	<td dir="ltr"><a href="{a href='counter/day/{year}/{month}'}" title="مشاهده آمار روزانه {name}">{name}</a></td>
	<td><div class="progress progress-{progress}"><span style="width: {percent}%;"><b>{percent}%</b></span></div></td>
	<td>{count}</td>
</tr>[/for month]
</tbody>
</table>
