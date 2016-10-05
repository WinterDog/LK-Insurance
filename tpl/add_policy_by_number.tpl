{extends "classes/content.tpl"}

{block "content" append}

	<div class="clearfix margin-b-lg">
		{$_PAGE->content}

		<p>
			<small class="text-muted">
				Обратите внимание — на даный момент мы работаем только с пятью страховыми компаниями:
				{foreach $companies as $item}
					{$item->title}{if (!$item@last)},{else}.{/if}
				{/foreach}
			</small>
		</p>
	</div>

	<form action="/{$_PAGE->name}/edit" class="form" id="content_form">
		<input name="id" type="hidden" value="{$policy->id|default}">
		<input name="insurer_type" type="hidden" value="1">

		<h5>Информация о полисе:</h5>

		<div class="row">
			<div class="col-sm-6 col-md-3">
				<div class="form-group">
					<label class="control-label">Тип полиса *</label>
					<select class="form-control" name="policy_type_id">
						<option value="">-</option>
						{foreach $policy_types as $item}
							<option
								value="{$item->id}"
								{if ((isset($policy)) && ($policy->policy_type_id == $item->id))}selected{/if}
							>
								{$item->title}
							</option>
						{/foreach}
					</select>
				</div>
			</div>
	
			<div class="col-sm-6 col-md-3">
				<div class="form-group">
					<label class="control-label">Страховая компания *</label>
					<select class="form-control" name="company_id">
						<option value="">-</option>
						{foreach $companies as $item}
							<option
								value="{$item->id}"
								{if ((isset($policy)) && ($policy->company_id == $item->id))}selected{/if}
							>
								{$item->title}
							</option>
						{/foreach}
					</select>
				</div>
			</div>
	
			<div class="col-sm-6 col-md-3">
				<div class="form-group">
					<label class="control-label">Номер полиса *</label>
					<input class="form-control" name="number" type="text" value="{$policy->number|default}">
				</div>
			</div>
	
			<div class="col-sm-6 col-md-3">
				<div class="form-group">
					<label class="control-label">Дата начала действия *</label>
					<input class="form-control" name="from_date" type="text" value="{$policy->date_from|default}">
				</div>
			</div>
		</div>

		<h5>Страхователь (человек, оформивший страховой договор):</h5>

		<div class="row">
			<div class="col-sm-6 col-md-3">
				<div class="form-group">
					<label class="control-label">Фамилия *</label>
					<input
						class="form-control"
						jf_data_group="insurer"
						name="surname"
						type="text"
						value="{$policy->insurer->surname|default}">
				</div>
			</div>
	
			<div class="col-sm-6 col-md-3">
				<div class="form-group">
					<label class="control-label">Имя *</label>
					<input
						class="form-control"
						jf_data_group="insurer"
						name="name"
						type="text"
						value="{$policy->insurer->name|default}">
				</div>
			</div>
	
			<div class="col-sm-6 col-md-3">
				<div class="form-group">
					<label class="control-label">Отчество</label>
					<input
						class="form-control"
						jf_data_group="insurer"
						name="father_name"
						type="text"
						value="{$policy->insurer->father_name|default}">
				</div>
			</div>
		</div>

		<div class="text-center margin-t">
			<button class="btn btn-success" type="submit">
				<span class="fa fa-check"></span>
				{if (!isset($policy))}Добавить{else}Сохранить{/if}
			</button>
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
		$(function ()
		{
			SetDatePicker(
				$('[name="from_date"]'));

			$('#content_form').submit(function ()
			{
				submit_data(this,
				{
					success: function (xhr)
					{
						OpenUrl('/add_policy_by_number_success/');
					},
				});
				return false;
			});
		});
	</script>

{/block}