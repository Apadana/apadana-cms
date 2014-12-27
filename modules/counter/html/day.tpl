<table class="apadana-table module-counter" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th style="width:20px">شماره</th>
	<th style="width:140px">سال</th>
	<th>نمودار</th>
	<th style="width:20px">تعداد</th>
</tr>
</thead>
<tbody>[for day]
<tr class="{odd-even}">
	<td>{number}</td>
	<td dir="ltr">{name}</td>
	<td>[all]--[/all][not-all]<div class="progress progress-{progress}"><span style="width: {percent}%;"><b>{percent}%</b></span></div>[/not-all]</td>
	<td>{count}</td>
</tr>[/for day]
</tbody>
</table>
