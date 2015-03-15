<style>
.apadana_calendar_friday{
	color: red;
}
.apadana_calendar_top{
	text-align: center;
}
.apadana_calendar_today{
	border: 1px solid #000;
}
.apadana_calendar_active{
	border: 1px solid #000;
	background: #FFF;
}
</style>
<script type="text/javascript">
	function posts_calendar( year , month , dir ){
		//alert(year+' '+month+' '+'&c='+year+'&d='+month)
		$.ajax({
			type : "GET",
			url : apadana.site.url + '?a=posts&b=calendar&c='+year+'&d='+month,
			beforeSend : function(){
				apadana.loading(1);
			},
			success: function(data) {
				$('#posts_calendar').slideUp('slow', function(){
					$(this).html(data).slideDown('slow');
				});
			},
			complete : function(){
				apadana.loading(0);
			},
			error : function(){
				alert('در ارتباط خطایی پیش آمده است!');
			}
		});
	}
</script>
<div id="posts_calendar">
	<table class="apadana_calendar_table">
		<tr>
			<td colspan="7" class="apadana_calendar_top">
			<a href="{site-url}" onclick="posts_calendar({next_month},'left'); return false;" >&laquo;</a>
			&nbsp;&nbsp;&nbsp;&nbsp;
			{full_name}
			&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="{site-url}" onclick="posts_calendar({prev_month},'right'); return false;" >&raquo;</a>
			</td>
		</tr>
		<tr>
			<th class="apadana_calendar_head">ش</th>
			<th class="apadana_calendar_head">ی</th>
			<th class="apadana_calendar_head">د</th>
			<th class="apadana_calendar_head">س</th>
			<th class="apadana_calendar_head">چ</th>
			<th class="apadana_calendar_head">پ</th>
			<th class="apadana_calendar_head">ج</th>
		</tr>
		<tr>
		[before]<td colspan="{before_colspan}"></td>[/before]
		
		[for day]
			<td class="{class}">
				[active]<a href="{url}" title="مشاهده پست های {day} {full_name}">[/active]
					{day}
				[active]</a>[/active]
			</td>
			[tr]</tr><tr>[/tr]
		[/for day]

		[after]<td colspan="{after_colspan}"></td>[/after]
		</tr>
	</table>
</div>