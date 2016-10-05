{extends "classes/content.tpl"}

{block "content" append}

	{include "inc/auth_login_reg.tpl"}

	<script>
		$(function ()
		{
			$('#login-form').submit(function ()
			{
				submit_data(this,
				{
					success: function ()
					{
						OpenUrl();
					},
				});
				return false;
			});

			$('#reg-form').submit(function ()
			{
				submit_data(this,
				{
					success: function ()
					{
						OpenUrl();
					},
				});
				return false;
			});
		});
	</script>

{/block}