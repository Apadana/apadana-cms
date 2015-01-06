[not-ajax]<div class="module-voting" id="module-voting-ajax-{key}">[/not-ajax]
<div class="vote-title" style="margin-bottom:5px"><b>{title}</b></div>
{message}[form]
<form id="module-voting-form-{key}" onsubmit="return false">[for vote]
<label><input type="radio" name="vote" value="{name}" />&nbsp;{name}</label><br />[/for vote]
<input type="hidden" name="id" value="{id}" />
<button onclick="$.ajax({type:'post',url:'{site-url}?a=voting&b=save&key={key}',data:$('#module-voting-form-{key}').serialize(),success: function(data){$('#module-voting-ajax-{key}').slideUp('slow',function(){$(this).html(data).slideDown('slow')})},beforeSend:function(){apadana.loading(1)},complete:function(){apadana.loading(0)},error:function(){alert('در ارتباط خطايي رخ داده است!')}})" style="margin-top:4px">{button}</button>
</form>
[/form][not-form]
<div class="vote-table">[for vote]
<div class="vote">{name} - {count} ({percent}%)</div>
<div class="progress[show-percent] polled[/show-percent][not-show-percent] voted[/not-show-percent] progress-{progress}"><span style="width: {percent}%;">[show-percent]<b>{percent}%</b>[/show-percent][not-show-percent]&nbsp;[/not-show-percent]</span></div>[/for vote]
</div>
[/not-form]
<center style="margin-top:5px">مجموع آرا: ({total})<br />[form]<a class="vote-show" href="{a href='voting/result/{id}'}" onclick="$.ajax({type:'get',url:'{site-url}?a=voting&b=result&c={id}&vote=0&key={key}',success: function(data){$('#module-voting-ajax-{key}').slideUp('slow',function(){$(this).html(data).slideDown('slow')})},beforeSend:function(){apadana.loading(1)},complete:function(){apadana.loading(0)},error:function(){alert('در ارتباط خطايي رخ داده است!')}}); return false">نتایج نظرسنجی</a><br />[/form][not-form]<a class="vote-show" href="{a href='voting/result/{id}'}" onclick="$.ajax({type:'get',url:'{site-url}?a=voting&b=result&c={id}&vote=1&key={key}',success: function(data){$('#module-voting-ajax-{key}').slideUp('slow',function(){$(this).html(data).slideDown('slow')})},beforeSend:function(){apadana.loading(1)},complete:function(){apadana.loading(0)},error:function(){alert('در ارتباط خطايي رخ داده است!')}}); return false">فرم ثبت نظر</a><br />[/not-form]<a class="other-votes" href="{a href='voting'}" title="نظرسنجی های دیگر">نظرسنجی های دیگر</a></center>
[not-ajax]</div>[/not-ajax]