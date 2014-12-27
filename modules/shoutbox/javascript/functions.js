apadana.shoutbox = {
	get: function(type)
	{
		if (type == 'ready')
		{
			$.ajax({
				type: 'GET',
				url: apadana.site.url + '?a=shoutbox&b=content',
				success: function(data) {
					$('div#shoutbox-content').slideUp('slow', function(){
						$(this).html(data).slideDown('slow');
					});
				}
			})
		}
		else
		{
			$.ajax({
				type: 'GET',
				url: apadana.site.url + '?a=shoutbox&b=content',
				beforeSend: function()
				{
					apadana.loading(1);
				},
				success: function(data)
				{
					$('div#shoutbox-content').slideUp('slow', function(){
						$(this).html(data).slideDown('slow', function(){
							$(this).animate({scrollTop:0}, 800)
						});
					});
				},
				error: function()
				{
					alert('در ارتباط خطايي رخ داده است!');
				},
				complete: function()
				{
					apadana.loading(0);
				}
			})
		}
	},

	send: function()
	{
		apadana.hideID('shoutbox-smiles');
		$.ajax({
			type: 'POST',
			url: apadana.site.url + '?a=shoutbox&b=send',
			data: 'msg=' + encodeURIComponent(apadana.value('shoutbox-textarea')),
			beforeSend: function()
			{
				apadana.loading(1);
			},
			success: function(data)
			{
				$('div#shoutbox-content').slideUp('slow', function(){
					$(this).html(data).slideDown('slow', function(){
						$(this).animate({scrollTop:0}, 800)
					});
				});
			},
			error: function()
			{
				alert('در ارتباط خطايي رخ داده است!');
			},
			complete: function()
			{
				apadana.loading(0);
			}
		});
	},

	createSmiles: function()
	{
		var test = apadana.trim(apadana.html('shoutbox-smiles'));

		if (test == '')
		{
			var smiles = '';

			for (i = 1; i <= 75; i++)
			{
				if (i == 50 || i == 65) continue;
				smiles += '<img src="'+apadana.site.url+'engine/images/smiles/'+i+'.gif" class="apadana-smiles" onclick="apadana.shoutbox.smileAdd('+i+')" />'
			}

			apadana.html('shoutbox-smiles', smiles);
		}
	},

	smileAdd: function(s)
	{
		var msg = apadana.value('shoutbox-textarea');

		if (msg == 'پیام شما ...')
		{
			var msg = '';
		}

		apadana.value('shoutbox-textarea', msg+' :s-'+s+': ');
		apadana.$('shoutbox-textarea').focus();
		apadana.hideID('shoutbox-smiles');
	}
}