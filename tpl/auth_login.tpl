{extends "classes/content.tpl"}

{block "content" append}

	<input id="referer" type="hidden" value="{$referer|default:'/'}">

	{include "inc/auth_login_reg.tpl"}

	<script>
		$(function ()
		{
			$('#login').focus();

			$('#login-form').submit(function ()
			{
				submit_data(this,
				{
					success: function (xhr)
					{
						if ($('#referer').val())
							OpenUrl($('#referer').val());
						else
							OpenUrl('/');
					},
					error: function ()
					{
						$('#login').focus();
					},
				});
				return false;
			});

			$('#reg-form').submit(function ()
			{
				submit_data(this,
				{
					success: function (xhr)
					{
						OpenUrl('/auth_registration_confirmed/');
					},
				});
				return false;
			});
		});
	</script>

{/block}