[not-ajax]<script language="JavaScript" type="text/javascript">/*<![CDATA[*/$(document).ready(function(){apadana.shoutbox.get('ready')})/*]]>*/</script>
<div id="module-shoutbox" class="module-shoutbox">
<div class="shoutbox-header">آخرین پیام ها&nbsp;<a href="#refresh" onclick="apadana.shoutbox.get('refresh');return false"><img src="{site-url}engine/images/icons/clock.png" title="تازه سازی فهرست پیام ها" /></a>&nbsp;<a href="{a href='shoutbox'}"><img src="{site-url}engine/images/icons/balloon.png" title="آرشیو پیام ها" /></a></div>
<div id="shoutbox-content" class="shoutbox-content" style="overflow-y:auto;overflow-x:hidden;height:250px">
<center><img src="{site-url}engine/images/loading/loader-2.gif" style="margin-top:50%" /></center>[/not-ajax][ajax]
[for message]<div class="shoutbox-row {odd-even}">
<a href="{a href='account/profile/{member}'}" title="مشاهده پروفایل {member}"><img src="{site-url}engine/images/icons/user.png" />&nbsp;<b>{member}</b></a>[delete]&nbsp;<a href="#delete" onclick="if(confirm('آیا از حذف این پیام اطمینان دارید؟')) apadana.ajax({method:'POST',action:'{a href='shoutbox/delete'}',data:'id={id}',success:function(a){apadana.html('shoutbox-content', a)}});return false"><img src="{site-url}engine/images/icons/bullet_delete.png" title="حذف پیام شماره {id}" /></a>[/delete]<br>
{message}
<div class="shoutbox-time">{time}</div>
</div>
[/for message]
[/ajax][not-ajax]</div>
<!-- shoutbox-content -->[group=5]
<div class="shoutbox-error">فقط اعضا می توانند پیام ارسال کنند!</div>[/group][not-group=5]
<textarea id="shoutbox-textarea" class="shoutbox-textarea" onblur="if(this.value=='') this.value='پیام شما ...';" onfocus="if(this.value=='پیام شما ...') this.value='';">پیام شما ...</textarea>
<button class="shoutbox-send" onclick="apadana.shoutbox.send()">ارسال پیام</button>&nbsp;<button class="shoutbox-smiles" onclick="apadana.shoutbox.createSmiles();apadana.changeShow('shoutbox-smiles')">شکلک ها</button>
<div id="shoutbox-smiles" style="display:none"></div>[/not-group]
</div>
<!-- shoutbox -->[/not-ajax]