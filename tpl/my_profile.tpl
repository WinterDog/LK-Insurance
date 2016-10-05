{extends "classes/content.tpl"}

{block "content" append}
	{*
	<link rel="stylesheet" href="/lib/fullscreen-form/css/normalize.css">
	<link rel="stylesheet" href="/lib/fullscreen-form/css/demo.css">
	<link rel="stylesheet" href="/lib/fullscreen-form/css/component.css">
	<link rel="stylesheet" href="/lib/fullscreen-form/css/cs-select.css">
	<link rel="stylesheet" href="/lib/fullscreen-form/css/cs-skin-boxes.css">

	<script src="/lib/fullscreen-form/js/modernizr.custom.js"></script>
	<script src="/lib/fullscreen-form/js/classie.js"></script>
	<script src="/lib/fullscreen-form/js/selectFx.js"></script>
	<script src="/lib/fullscreen-form/js/fullscreenForm.js"></script>
	*}

	{if (isset($profile_changed))}
		<p class="alert alert-success alert-dismissible fade in" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" class="fa fa-times"></span></button>
			Профиль был успешно обновлён.
		</p>
	{/if}

	{if (isset($password_changed))}
		<p class="alert alert-success alert-dismissible fade in" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" class="fa fa-times"></span></button>
			Пароль был успешно изменён.
		</p>
	{/if}

	<h3>Профиль</h3>

	<form action="/{$_PAGE->name}/edit" class="form-horizontal" id="profile-edit-form">
		<input name="id" type="hidden" value="{$user->id|default}">

		<div class="row form-group">
			<label class="col-sm-3 control-label">Логин</label>
			<div class="col-sm-9">
				<p class="form-control-static">
					{$user->login}
				</p>
			</div>
		</div>

		<div class="row form-group">
			<label class="col-sm-3 control-label">Ваше имя *</label>
			<div class="col-sm-9">
				<input class="form-control" name="nickname" type="text" value="{$user->nickname|default}">
			</div>
		</div>

		<div class="row form-group">
			<label class="col-sm-3 control-label">Электронная почта *</label>
			<div class="col-sm-9">
				<input class="form-control" name="email" type="text" value="{$user->email|default}">
			</div>
		</div>

		<div class="row form-group">
			<label class="col-sm-3 control-label">Телефон</label>
			<div class="col-sm-9">
				<input class="form-control" name="phone" type="text" value="{$user->phone|default}">
			</div>
		</div>

		<div class="text-center">
			<button class="btn btn-success" type="submit">{if (!isset($user))}Добавить{else}Сохранить{/if}</button>
		</div>
	</form>

	<h3 class="margin-t-lg">Смена пароля</h3>

	<form action="/{$_PAGE->name}/password_edit" class="form-horizontal" id="password-edit-form">
		<input name="id" type="hidden" value="{$user->id|default}">

		<div class="row form-group">
			<label class="col-sm-3 control-label">Текущий пароль *</label>
			<div class="col-sm-9">
				<input class="form-control" name="cur_password" type="password">
			</div>
		</div>

		<div class="row form-group">
			<label class="col-sm-3 control-label">Новый пароль *</label>
			<div class="col-sm-9">
				<input class="form-control" name="password" type="password">
			</div>
		</div>

		<div class="row form-group">
			<label class="col-sm-3 control-label">Новый пароль ещё раз *</label>
			<div class="col-sm-9">
				<input class="form-control" name="password_repeat" type="password">
			</div>
		</div>

		<div class="text-center">
			<button class="btn btn-success" type="submit">Изменить пароль</button>
		</div>
	</form>

	{*
	<div class="fs-form-wrap" id="fs-form-wrap">
		<form id="myform" class="fs-form fs-form-full" autocomplete="off">
			<ol class="fs-fields">
				<li>
					<label class="fs-field-label fs-anim-upper" for="q1">What's your name?</label>
					<input class="fs-anim-lower" id="q1" name="q1" type="text" placeholder="Dean Moriarty" required/>
				</li>
				<li>
					<label class="fs-field-label fs-anim-upper" for="q2" data-info="We won't send you spam, we promise...">What's your email address?</label>
					<input class="fs-anim-lower" id="q2" name="q2" type="email" placeholder="dean@road.us" required/>
				</li>
				<li data-input-trigger>
					<label class="fs-field-label fs-anim-upper" for="q3" data-info="This will help us know what kind of service you need">What's your priority for your new website?</label>
					<div class="fs-radio-group fs-radio-custom clearfix fs-anim-lower">
						<span><input id="q3b" name="q3" type="radio" value="conversion"/><label for="q3b" class="radio-conversion">Sell things</label></span>
						<span><input id="q3c" name="q3" type="radio" value="social"/><label for="q3c" class="radio-social">Become famous</label></span>
						<span><input id="q3a" name="q3" type="radio" value="mobile"/><label for="q3a" class="radio-mobile">Mobile market</label></span>
					</div>
				</li>
				<li data-input-trigger>
					<label class="fs-field-label fs-anim-upper" data-info="We'll make sure to use it all over">Choose a color for your website.</label>
					<select class="cs-select cs-skin-boxes fs-anim-lower">
						<option value="" disabled selected>Pick a color</option>
						<option value="#588c75" data-class="color-588c75">#588c75</option>
						<option value="#b0c47f" data-class="color-b0c47f">#b0c47f</option>
						<option value="#f3e395" data-class="color-f3e395">#f3e395</option>
						<option value="#f3ae73" data-class="color-f3ae73">#f3ae73</option>
						<option value="#da645a" data-class="color-da645a">#da645a</option>
						<option value="#79a38f" data-class="color-79a38f">#79a38f</option>
						<option value="#c1d099" data-class="color-c1d099">#c1d099</option>
						<option value="#f5eaaa" data-class="color-f5eaaa">#f5eaaa</option>
						<option value="#f5be8f" data-class="color-f5be8f">#f5be8f</option>
						<option value="#e1837b" data-class="color-e1837b">#e1837b</option>
						<option value="#9bbaab" data-class="color-9bbaab">#9bbaab</option>
						<option value="#d1dcb2" data-class="color-d1dcb2">#d1dcb2</option>
						<option value="#f9eec0" data-class="color-f9eec0">#f9eec0</option>
						<option value="#f7cda9" data-class="color-f7cda9">#f7cda9</option>
						<option value="#e8a19b" data-class="color-e8a19b">#e8a19b</option>
						<option value="#bdd1c8" data-class="color-bdd1c8">#bdd1c8</option>
						<option value="#e1e7cd" data-class="color-e1e7cd">#e1e7cd</option>
						<option value="#faf4d4" data-class="color-faf4d4">#faf4d4</option>
						<option value="#fbdfc9" data-class="color-fbdfc9">#fbdfc9</option>
						<option value="#f1c1bd" data-class="color-f1c1bd">#f1c1bd</option>
					</select>
				</li>
				<li>
					<label class="fs-field-label fs-anim-upper" for="q4">Describe how you imagine your new website</label>
					<textarea class="fs-anim-lower" id="q4" name="q4" placeholder="Describe here"></textarea>
				</li>
				<li>
					<label class="fs-field-label fs-anim-upper" for="q5">What's your budget?</label>
					<input class="fs-mark fs-anim-lower" id="q5" name="q5" type="number" placeholder="1000" step="100" min="100"/>
				</li>
			</ol><!-- /fs-fields -->
			<button class="fs-submit" type="submit">Send answers</button>
		</form><!-- /fs-form -->
	</div><!-- /fs-form-wrap -->
	*}

	<script>
		{*
		(function()
		{
			var formWrap = document.getElementById( 'fs-form-wrap' );

			[].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach(function (el)
			{	
				new SelectFx(
					el,
					{
						stickyPlaceholder:	false,
						onChange:			function (val)
						{
							document.querySelector('span.cs-placeholder').style.backgroundColor = val;
						},
					});
			});

			new FForm( formWrap, {
				onReview : function() {
					classie.add( document.body, 'overview' ); // for demo purposes only
				}
			} );
		})();
		*}

		$(function ()
		{
			$('[name="phone"]').mask('+7 (999) 999-99-99');

			$('#profile-edit-form').submit(function ()
			{
				submit_data(this,
				{
					success: function (xhr)
					{
						OpenUrl('/{$_PAGE->name}/');
					},
				});
				return false;
			});
			$('#password-edit-form').submit(function ()
			{
				submit_data(this,
				{
					success: function (xhr)
					{
						OpenUrl('/{$_PAGE->name}/');
					},
				});
				return false;
			});
		});
	</script>

{/block}