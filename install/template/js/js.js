function set_percent(number)
{
	if (apadana.$('percent').style.width != number+'px')
	{
		$('#percent').animate({width: number+'px'}, 900)
	}
}

function install_load(action)
{
	$.ajax({
		type: 'GET',
		url: 'index.php?section=install&action='+action,
		beforeSend: function()
		{
			apadana.loading(1);
		},
		success: function(data)
		{
			$('div#main').slideUp('slow', function(){
				$(this).html(data).slideDown('slow', function(){
					apadana.scroll('div#content')
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

function install_check(action)
{
	switch (action)
	{
		case 'config':
			$.ajax({
				type: 'POST',
				url: 'index.php?section=install&action=config-check',
				data: $('#form-config').serialize(),
				dataType: 'json',
				beforeSend: function()
				{
					apadana.loading(1);
				},
				success: function(a)
				{
					if (a.result == 'connect')
					{
						if (apadana.$('input-host').value != 'localhost')
						{
							apadana.$('input-host').style.border = '#FF0000 1px solid';
						}
						apadana.$('input-user').style.border = '#FF0000 1px solid';
						apadana.$('input-password').style.border = '#FF0000 1px solid';
						apadana.$('input-name').style.border = '#CCCCCC 1px solid';
						apadana.$('input-prefix').style.border = '#CCCCCC 1px solid';
					}
					else if (a.result == 'select')
					{
						apadana.$('input-host').style.border = '#CCCCCC 1px solid';
						apadana.$('input-user').style.border = '#CCCCCC 1px solid';
						apadana.$('input-password').style.border = '#CCCCCC 1px solid';
						apadana.$('input-name').style.border = '#FF0000 1px solid';
						apadana.$('input-prefix').style.border = '#CCCCCC 1px solid';
					}
					else if (a.result == 'prefix')
					{
						apadana.$('input-host').style.border = '#CCCCCC 1px solid';
						apadana.$('input-user').style.border = '#CCCCCC 1px solid';
						apadana.$('input-password').style.border = '#CCCCCC 1px solid';
						apadana.$('input-name').style.border = '#CCCCCC 1px solid';
						apadana.$('input-prefix').style.border = '#FF0000 1px solid';
					}
					else if (a.result == 'success')
					{
						apadana.$('input-host').style.border = 'green 1px solid';
						apadana.$('input-user').style.border = 'green 1px solid';
						apadana.$('input-password').style.border = 'green 1px solid';
						apadana.$('input-name').style.border = 'green 1px solid';
						apadana.$('input-prefix').style.border = 'green 1px solid';
						apadana.$('button-disabled').disabled = '';
					}
					else
					{
						if (apadana.$('input-host').value != 'localhost')
						{
							apadana.$('input-host').style.border = '#FF0000 1px solid';
						}
						apadana.$('input-user').style.border = '#FF0000 1px solid';
						apadana.$('input-password').style.border = '#FF0000 1px solid';
						apadana.$('input-name').style.border = '#FF0000 1px solid';
						apadana.$('input-prefix').style.border = '#FF0000 1px solid';
						alert('یک خطای ناشناخته در ارتباط رخ داده است!')
					}
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
		break;
		
		case 'admin':
			$.ajax({
				type: 'POST',
				url: 'index.php?section=install&action=admin-check',
				data: $('#form-admin').serialize(),
				dataType: 'json',
				beforeSend: function()
				{
					apadana.loading(1);
				},
				success: function(a)
				{
					if (a.result == 'name')
					{
						apadana.$('input-name').style.border = '#FF0000 1px solid';
						apadana.$('input-pass1').style.border = '#CCCCCC 1px solid';
						apadana.$('input-pass2').style.border = '#CCCCCC 1px solid';
						apadana.$('input-email').style.border = '#CCCCCC 1px solid';
					}
					else if (a.result == 'pass')
					{
						apadana.$('input-pass1').style.border = '#FF0000 1px solid';
						apadana.$('input-pass2').style.border = '#FF0000 1px solid';
						apadana.$('input-name').style.border = '#CCCCCC 1px solid';
						apadana.$('input-email').style.border = '#CCCCCC 1px solid';
					}
					else if (a.result == 'email')
					{
						apadana.$('input-pass1').style.border = '#CCCCCC 1px solid';
						apadana.$('input-pass2').style.border = '#CCCCCC 1px solid';
						apadana.$('input-name').style.border = '#CCCCCC 1px solid';
						apadana.$('input-email').style.border = '#FF0000 1px solid';
					}
					else if (a.result == 'success')
					{
						apadana.$('input-pass1').style.border = 'green 1px solid';
						apadana.$('input-pass2').style.border = 'green 1px solid';
						apadana.$('input-name').style.border = 'green 1px solid'
						apadana.$('input-email').style.border = 'green 1px solid'
						apadana.$('button-disabled').disabled = '';
					}
					else
					{
						apadana.$('input-pass1').style.border = '#FF0000 1px solid';
						apadana.$('input-pass2').style.border = '#FF0000 1px solid';
						apadana.$('input-name').style.border = '#FF0000 1px solid';
						apadana.$('input-email').style.border = '#FF0000 1px solid';
						alert('یک خطای ناشناخته رخ داده است!')
					}
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
		break;
	}
}

function install_config()
{
	if (apadana.$('button-disabled').disabled != 'disabled')
	{
		$.ajax({
			type: 'POST',
			url: 'index.php?section=install&action=create-config',
			data: $('#form-config').serialize(),
			beforeSend: function()
			{
				apadana.loading(1);
			},
			success: function(data)
			{
				$('div#main').slideUp('slow', function(){
					$(this).html(data).slideDown('slow', function(){
						apadana.scroll('div#content')
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
	else
	{
		alert('ابتدا صحت اطلاعات وارد شده را چک کنید!')
	}
}

function install_admin()
{
	if (apadana.$('button-disabled').disabled != 'disabled')
	{
		$.ajax({
			type: 'POST',
			url: 'index.php?section=install&action=admin-insert',
			data: $('#form-admin').serialize(),
			beforeSend: function()
			{
				apadana.loading(1);
			},
			success: function(data)
			{
				$('div#main').slideUp('slow', function(){
					$(this).html(data).slideDown('slow', function(){
						apadana.scroll('div#content')
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
	else
	{
		alert('ابتدا صحت اطلاعات وارد شده را چک کنید!')
	}
}

function upgrade_load(action)
{
	$.ajax({
		type: 'GET',
		url: 'upgrade.php?action='+action,
		beforeSend: function()
		{
			apadana.loading(1);
		},
		success: function(data)
		{
			$('div#main').slideUp('slow', function(){
				$(this).html(data).slideDown('slow', function(){
					apadana.scroll('div#content')
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

function upgrade_check(action)
{
	switch (action)
	{
		case 'admin':
			$.ajax({
				type: 'POST',
				url: 'upgrade.php?action=admin-check',
				data: $('#form-admin').serialize(),
				dataType: 'json',
				beforeSend: function()
				{
					apadana.loading(1);
				},
				success: function(a)
				{
					if (a.result == 'password')
					{
						apadana.$('input-password').style.border = '#FF0000 1px solid';
					}
					else if (a.result == 'success')
					{
						apadana.$('input-password').style.border = 'green 1px solid';
						apadana.$('button-disabled').disabled = '';
					}
					else
					{
						apadana.$('input-password').style.border = '#FF0000 1px solid';
						alert('یک خطای ناشناخته رخ داده است!')
					}
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
		break;
	}
}