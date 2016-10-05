	{if (!$_CFG['debug'])}
		<div id="vk_api_transport"></div>

		<script>
			window.vkAsyncInit = function ()
			{
				VK.init(
				{
					apiId: 3476801,
					onlyWidgets: true,
				});
			};

			setTimeout(function ()
			{
				var el = document.createElement("script");
				el.type = "text/javascript";
				el.src = "http://vkontakte.ru/js/api/openapi.js";
				el.async = true;
				document.getElementById("vk_api_transport").appendChild(el);
			}, 0);
		</script>
	{/if}