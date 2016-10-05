{extends "classes/content_wide.tpl"}

{block "header_block"}{/block}
{block "header_title"}{$clinic->title}{/block}

{block "content_h1"}{/block}

{block "content" append}

	<section>
		<div class="row">
			<div class="col-md-8">
				{if (sizeof($clinic->affiliates) > 1)}
					<h6 class="text-muted margin-t">
						Адреса отделений:
					</h6>
					<ul>
						{foreach $clinic->affiliates as $affiliate}
							<li>
								{include "inc/dms/clinic/affiliate_address.tpl" affiliate=$affiliate}
							</li>
						{/foreach}
					</ul>
				{else}
					<h6 class="text-muted margin-t">
						Адрес:
					</h6>
					<p>
						{include "inc/dms/clinic/affiliate_address.tpl" affiliate=$clinic->affiliate}
					</p>
				{/if}

				<h6 class="text-muted margin-t">
					Контакты:
				</h6>
				<section>
					<ul class="list-unstyled">
						{if ($clinic->phone != '')}
							<li>
								<p><a href="tel:{$clinic->phone}">{$clinic->phone}</a></p>
							</li>
						{/if}
						{if ($clinic->url != '')}
							<li>
								<p><a href="{$clinic->url}" title="Открыть сайт клиники (в новой вкладке)">{$clinic->url}</a></p>
							</li>
						{/if}
						{if ($clinic->email != '')}
							<li>
								<p><a href="mailto:{$clinic->email}">{$clinic->email}</a></p>
							</li>
						{/if}
					</ul>
				</section>

				{if (sizeof($clinic->photos) > 1)}
	
					<h6 class="text-muted margin-t">
						Фото:
					</h6>
					<div class="row">
						{foreach $clinic->photos as $photo}
							<div class="col-xs-3">
								<a
									class="img-responsive"
									data-footer="{$photo['caption']}"
									data-gallery="clinic-{$clinic->id}"
									data-title="{$clinic->title}"
									data-toggle="lightbox"
									href="/upload_m/a/{$photo['src']}"
									no-ajax
								>
									<img alt="Фотография {$photo@index + 1}" class="img-responsive" src="/upload_m/a/thumbs/{$photo['src']}">
								</a>
							</div>
							{if ($photo@index == 3)}
								{break}
							{/if}
						{/foreach}
					</div>

					{if (sizeof($clinic->photos) > 3)}
						<div class="margin-t">
							<a
								class="btn btn-sm btn-default"
								data-footer="{$photo['caption']}"
								data-gallery="clinic-{$clinic->id}"
								data-title="{$clinic->title}"
								data-toggle="lightbox"
								href="/upload_m/a/{$photo['src']}"
								no-ajax
								role="button"
							>
								Ещё фото
								<span class="text-muted">{sizeof($clinic->photos) - 4}</span>
							</a>
						</div>

						<div hidden>
							{foreach $clinic->photos as $photo}
								{if ($photo@index <= 3)}
									{continue}
								{/if}
								<a
									data-footer="{$photo['caption']}"
									data-gallery="clinic-{$clinic->id}"
									data-title="{$clinic->title}"
									data-toggle="lightbox"
									href="/upload_m/a/{$photo['src']}"
								>
								</a>
							{/foreach}
						</div>
					{/if}

				{/if}
			</div>

			<div class="col-md-4">
				{include "inc/dms/clinic_map_sm.tpl" clinic=$clinic}
			</div>
		</div>
	</section>

	<section>
		{if ($clinic->description != '')}
			<hr>
			{$clinic->description}
		{/if}
	</section>

	{* if ((sizeof($clinic->tariffs['clinic_adult']) > 0) || (sizeof($clinic->tariffs['clinic_adult_special']) > 0)) *}
	{if (sizeof($clinic->tariffs['clinic_adult_special']) > 0)}
		<section>
			<h4 class="margin-t-lg">Программы ДМС (взрослые)</h4>

			{* foreach $clinic->tariffs['clinic_adult'] as $clinic_company}
				{foreach $clinic_company->programs as $program}
					{$companies[$program->company_id]->title}
					<h5>
						<a href="/dms_clinic_program_view/?type={$program->type}&id={$program->id}">{$program->title}</a>
					</h5>
				{/foreach}
			{/foreach *}
			{foreach $clinic->tariffs['clinic_adult_special'] as $clinic_company}
				{foreach $clinic_company->programs as $program}
					{$companies[$program->company_id]->title}
					<h5>
						<a href="/dms_clinic_program_view/?type={$program->type}&id={$program->id}">{$program->title}</a>
					</h5>
				{/foreach}
			{/foreach}
		</section>
	{/if}

	{* if ((sizeof($clinic->tariffs['clinic_child']) > 0) || (sizeof($clinic->tariffs['clinic_child_special']) > 0)) *}
	{if (sizeof($clinic->tariffs['clinic_child_special']) > 0)}
		<section>
			<h4 class="margin-t-lg">Программы ДМС (дети)</h4>

			{* foreach $clinic->tariffs['clinic_child'] as $clinic_company}
				{foreach $clinic_company->programs as $program}
					{$companies[$program->company_id]->title}
					<h5>
						<a href="/dms_clinic_program_view/?type={$program->type}&id={$program->id}">{$program->title}</a>
					</h5>
				{/foreach}
			{/foreach *}
			{foreach $clinic->tariffs['clinic_child_special'] as $clinic_company}
				{foreach $clinic_company->programs as $program}
					{$companies[$program->company_id]->title}
					<h5>
						<a href="/dms_clinic_program_view/?type={$program->type}&id={$program->id}">{$program->title}</a>
					</h5>
				{/foreach}
			{/foreach}
		</section>
	{/if}

	{if (sizeof($clinic->special_offers) > 0)}
		<section>
			<h4 class="margin-t-lg">Текущие акции</h4>
		
			{foreach $clinic->affiliates as $affiliate}
				<h5>
					<a href="{$offer->url}">{$offer->title}</a>
				</h5>
			{/foreach}
		</section>
	{/if}

	{if ($_PAGES['clinics_edit']->rights > 0)}
		<div class="text-center margin-t-xl">
			<a class="btn btn-warning" href="/clinics_edit/?id={$clinic->id}" role="button">
				<span class="fa fa-pencil"></span>
			</a>
			<button
				class="btn btn-danger"
				title="Удалить"
				type="button"
				onclick="RefItemDeleteForm({$clinic->id});"
			>
				<span class="fa fa-times"></span>
			</button>
		</div>
	
		{include "inc/modal_ref_dialog.tpl" page_name='clinics'}
	{/if}

{/block}