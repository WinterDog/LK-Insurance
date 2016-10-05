	<div class="col-sm-2 padding-tb">

		<section aria-labelledby="Фотографии">

			{if (sizeof($clinic->photos) > 0)}

				{foreach $clinic->photos as $photo}
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
					{break}
				{/foreach}

				{foreach $clinic->photos as $photo}
					{if ($photo@index <= 0)}
						{continue}
					{/if}
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
							<span class="text-muted">{sizeof($clinic->photos) - 1}</span>
						</a>
						{if ($photo@index >= 1)}
							{break}
						{/if}
					</div>
				{/foreach}

				<div hidden>
					{foreach $clinic->photos as $photo}
						{if ($photo@index <= 1)}
							{continue}
						{/if}
						<a
							data-footer="{$photo['caption']}"
							data-gallery="clinic-{$clinic->id}"
							data-title="{$clinic->title}"
							data-toggle="lightbox"
							href="/upload_m/a/{$photo['src']}"
						>
							{*<img alt="Фотография {$photo@index + 1}" class="img-responsive" src="/upload_m/a/thumbs/{$photo['src']}">*}
						</a>
					{/foreach}
				</div>

			{else}

				<img alt="Нет фотографий" class="img-responsive" src="/css/img/no-photo.png">

			{/if}

		</section>

	</div>{* .col *}

	<div class="col-sm-8 padding-tb">

		<section>

			<h4 aria-labelledby="Название клиники" sf-clinic-id="{$clinic->id}" sf-id="title">
				<a
					class="no-color"
					href="/clinic_view/?id={$clinic->id}"
					target="_blank"
					title="Подробная информация о клинике (в новой вкладке)"
				>
					<span class="underline">{$clinic->title}</span>
				</a>
			</h4>

			<div>
				<section aria-labelledby="Адрес клиники или адреса отделений">
					<div>
						{if (sizeof($clinic->affiliates) == 1)}

							{* <span class="fa fa-map-marker margin-r-sm"></span> *}
							Адрес:
							{$clinic->affiliate->address}
							{if ({$clinic->affiliate->metro_station_id})}
								(м. {$clinic->affiliate->metro_station_title})
							{/if}
							<a
								href="https://maps.yandex.ru/?text={$clinic->affiliate->address}"
								target="_blank"
								title="Показать на Яндекс.Картах (в новой вкладке)"
							>
								<span class="fa fa-map-marker"></span>
							</a>

						{else}

							Отделений: {sizeof($clinic->affiliates)}
							
							<div style="display: none;">

								{foreach $clinic->affiliates as $affiliate}
									<ul class="list-unstyled">
										<li>
											{$affiliate->address}
											{if ({$affiliate->metro_station_id})}
												(м. {$affiliate->metro_station_title})
											{/if}
											<a
												href="https://maps.yandex.ru/?text={$affiliate->address}"
												target="_blank"
												title="Показать на Яндекс.Картах (в новой вкладке)"
											>
												<span class="fa fa-bullseye"></span>
											</a>
										</li>
									</ul>
								{/foreach}
							
							</div>

						{/if}
					</div>

					{if ($clinic->phone != '')}
						<div>
							{* <span class="fa fa-phone margin-r-sm"></span> *}
							Телефон:
							<a href="tel:{$clinic->phone}">{$clinic->phone}</a>
						</div>
					{/if}
					{if ($clinic->email != '')}
						<div>
							{* <span class="fa fa-envelope-o margin-r-sm"></span> *}
							Электронная почта:
							<a href="mailto:{$clinic->email}">{$clinic->email}</a>
						</div>
					{/if}
					{if ($clinic->url != '')}
						<div>
							{* <span class="fa fa-globe margin-r-sm"></span> *}
							Сайт:
							<a href="{$clinic->url}" title="Открыть сайт клиники (в новой вкладке)">{$clinic->url}</a>
						</div>
					{/if}
				</section>
			</div>

		</section>

	</div>{* .col *}
