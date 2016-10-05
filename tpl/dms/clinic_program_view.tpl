{extends "classes/content.tpl"}

{block "header_block"}{/block}
{block "header_title"}Программа ДМС "{$program->title}"{/block}

{block "content_title"}{/block}

{block "content" append}

	<div class="clearfix">
		{$_PAGE->content}
	</div>

	<section>
		<div class="row">
			<div class="col-md-8">
				<h6 class="text-muted">
					Страховая компания:
				</h6>
				<p>
					<strong>
						{$company->title}
					</strong>
				</p>

				<h6 class="text-muted margin-t">
					Обслуживание в
					{if (sizeof($clinic->affiliates) > 1)}
						сети клиник:
					{else}
						клинике:
					{/if}
				</h6>
				<p>
					<strong>
						{$clinic->title}
					</strong>
				</p>

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

				{*
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
				*}
			</div>

			<div class="col-md-4">
				{include "inc/dms/clinic_map_sm.tpl" clinic=$clinic}
			</div>
		</div>
	</section>

	<section>
		{if ($program->description != '')}
			<h3 class="margin-t">
				Описание программы
			</h3>

			{$program->description}
		{/if}

		{if (in_array(3, $program->service_type_ids))}
			{if (($program->clinic_desc != '') || (sizeof($clinic_option_groups) > 0))}

				<h3 class="margin-t">
					Поликлиническое обслуживание
				</h3>

				<section>
					{$program->clinic_desc}
				</section>

				<section>
					<p>
						В программу поликлинического обслуживания входит:
					</p>

						{foreach $clinic_option_groups as $clinic_option_group}
							{if (!in_array($clinic_option_group->id, $program->clinic_option_group_ids))}
								{continue}
							{/if}
			
							<p><strong>{$clinic_option_group->title}:</strong></p>
							<ul>
								{foreach $clinic_options[$clinic_option_group->id] as $clinic_option}
									{if (!in_array($clinic_option_group->id, $program->clinic_option_ids))}
										{continue}
									{/if}
		
									<li>
										{$clinic_option->title}
									</li>
								{/foreach}
							</ul>
						{/foreach}
				</section>

			{/if}
		{/if}

		{if (in_array(4, $program->service_type_ids))}
			{if ($program->doctor_desc != '')}
				<h3 class="margin-t">
					Вызов врача
				</h3>

				{$program->doctor_desc}
			{/if}
		{/if}

		{if (in_array(1, $program->service_type_ids))}
			{if ($program->ambulance_desc != '')}
				<h3 class="margin-t">
					Скорая помощь
				</h3>

				{$program->ambulance_desc}
			{/if}
		{/if}

		{if (in_array(2, $program->service_type_ids))}
			{if ($program->dentist_desc != '')}
				<h3 class="margin-t">
					Стоматология
				</h3>

				{$program->dentist_desc}
			{/if}
		{/if}

		{if ($program->exceptions != '')}
			<h3 class="margin-t">
				Исключения
			</h3>

			{$program->exceptions}
		{/if}
	</section>

	<script>
		$(function ()
		{
			InitLightbox();
		});
	</script>

{/block}