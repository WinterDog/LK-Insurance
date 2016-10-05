	<!-- vk.com - Авторизация -->
	<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?49"></script>








	<!-- vk.com - Авторизация -->
	<!--
	<script type="text/javascript">
		VK.init({apiId: API_ID, onlyWidgets: true});
	</script>

	<div id="vk_auth"></div>

	<script type="text/javascript">
		VK.Widgets.Auth("vk_auth",
		{
			width: "200px",
			onAuth: function(data)
			{
				alert('user '+data['uid']+' authorized');
			}
		});
	</script>
	<!-- vk.com - Авторизация -->

	<!-- vk.com - Кнопка "Мне нравится" -->
	<!--
	<div id="vk_like"></div>

	<script type="text/javascript">
		VK.Widgets.Like("vk_like", {type: "button", verb: 1});
	</script>
	<!-- vk.com - Кнопка "Мне нравится" -->

	<!-- facebook.com - Авторизация -->
	<!--
	<div id="fb-root"></div>

	<script>
		window.fbAsyncInit = function()
		{
			FB.init(
			{
				appId      : 'YOUR_APP_ID',
				status     : true, 
				cookie     : true,
				xfbml      : true,
				oauth      : true,
			});
		};
		(function(d)
		{
			var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
			js = d.createElement('script'); js.id = id; js.async = true;
			js.src = "//connect.facebook.net/en_US/all.js";
			d.getElementsByTagName('head')[0].appendChild(js);
		}(document));
	</script>

	<div class="fb-login-button">Войти через Facebook</div>
	<!-- facebook.com - Авторизация -->