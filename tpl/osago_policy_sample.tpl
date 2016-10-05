{extends "classes/content.tpl"}

{block "breadcrumb"}
	<small>
		<ol class="breadcrumb margin-b-sm">
			{foreach $_PAGE->parents as $parent_page}
				<li>
					<a href="/{$parent_page->name}/{if ($parent_page->name == 'osago_policy')}?id={$policy->id}{/if}">
						{$parent_page->title}
					</a>
				</li>
			{/foreach}
			<li class="active">{$_PAGE->title}</li>
		</ol>
	</small>
{/block}

{block "content" append}

	<div class="osago-sample-wrap">

		<img alt="Образец полиса ОСАГО" class="osago-sample-img" src="/css/img/osago_sample_lg.jpg">

		<div class="cell-output" style="left: 38.3em; top: 11.7em;">
			00
		</div>
		<div class="cell-output" style="left: 43.4em; top: 11.7em;">
			00
		</div>
		<div class="cell-output" style="left: 49.8em; top: 11.7em;">
			{$policy->from_date_expl[0]}
		</div>
		<div class="cell-output" style="left: 53.8em; top: 11.7em;">
			{$policy->from_date_expl[1]}
		</div>
		<div class="cell-output" style="left: 59.8em; top: 11.7em;">
			{$policy->from_date_expl[2]}
		</div>

		<div class="cell-output" style="left: 49.8em; top: 14.15em;">
			{$policy->to_date_expl[0]}
		</div>
		<div class="cell-output" style="left: 53.8em; top: 14.15em;">
			{$policy->to_date_expl[1]}
		</div>
		<div class="cell-output" style="left: 59.8em; top: 14.15em;">
			{$policy->to_date_expl[2]}
		</div>

		<div class="cell-output" style="left: 2.5em; top: 20.2em;">
			{$policy->from_date_expl[0]}
		</div>
		<div class="cell-output" style="left: 6.5em; top: 20.2em;">
			{$policy->from_date_expl[1]}
		</div>
		<div class="cell-output" style="left: 11.7em; top: 20.2em;">
			{$policy->from_date_expl[2]}
		</div>

		<div class="cell-output" style="left: 18.7em; top: 20.2em;">
			{$policy->to_date_expl[0]}
		</div>
		<div class="cell-output" style="left: 22.6em; top: 20.2em;">
			{$policy->to_date_expl[1]}
		</div>
		<div class="cell-output" style="left: 27.8em; top: 20.2em;">
			{$policy->to_date_expl[2]}
		</div>

		<div style="left: 1.2em; top: 27.0em;">
			{$policy->insurer->fio|default}
		</div>
		<div style="left: 1.2em; top: 32.6em;">
			{$policy->policy_data->owner->fio|default}
		</div>

		<div style="left: 1.2em; top: 39.65em;">
			{$policy->policy_data->car->mark_title} {$policy->policy_data->car->model_title}
		</div>
		<div class="cell-output" style="left: 22.1em; top: 39.65em;">
			{$policy->policy_data->car->vin}
		</div>
		<div style="left: 50.4em; top: 39.65em;">
			{$policy->policy_data->car->register_number}RUS
		</div>

		<div style="left: 8.4em; top: 43.5em;">
			ПТС
		</div>
		<div style="left: 43.6em; top: 43.5em;">
			{$policy->policy_data->car->pts_series}
		</div>
		<div style="left: 56.6em; top: 43.5em;">
			{$policy->policy_data->car->pts_number}
		</div>

		{if (sizeof($policy->policy_data->drivers) > 0)}

			<div style="left: 50.6em; top: 48.3em;">
				X
			</div>

			{foreach $policy->policy_data->drivers as $driver}
				<div style="left: 1.5em; top: {52.2 + 1.75 * $driver@index}em;">
					{$driver@index + 1}
				</div>
				<div style="left: 3.7em; top: {52.2 + 1.75 * $driver@index}em;">
					{$driver->fio}
				</div>
				<div style="left: 44.0em; top: {52.2 + 1.75 * $driver@index}em;">
					{$driver->license->license_series} {$driver->license->license_number}
				</div>
			{/foreach}

		{else}

			<div style="left: 50.6em; top: 46.7em;">
				X
			</div>

		{/if}

		<div style="left: 12.4em; top: 72.1em;">
			{$policy->total_sum_f} ({$policy->total_sum_w})
		</div>

	</div>

	<div class="form-group margin-t-lg text-center">
		<a class="btn btn-default" href="/osago_policy/?id={$policy->id}">
			&laquo; Назад
		</a>
	</div>

{/block}