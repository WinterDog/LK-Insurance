	<div class="panel panel-default">
		<div class="panel-heading">
			Водитель
		</div>

		<div class="panel-body padding-b-0">

			<div class="row">
				<div class="col-xs-12">
					<div class="form-group">
						<label class="control-label">
							Фамилия имя отчество, дата рождения
						</label>
						<p class="form-control-static">
							{if ($driver->fio != '')}
								{$driver->fio},
							{else}
								<span class="text-muted">[не указано]</span>,
							{/if}

							{if ($driver->gender)}
								({if ($driver->gender == 1)}М{else}Ж{/if}),
							{/if}

							{$driver->birthday} г.
							({$driver->full_years})
						</p>
					</div>
				</div>

				<div class="col-xs-12">
					<div class="form-group">
						<label class="control-label">
							Водительское удостоверение
						</label>
						<p class="form-control-static">
							серия
							{if ($driver->license->license_series != '')}
								{$driver->license->license_series}
							{else}
								<span class="text-muted">[не указано]</span>
							{/if}

							№
							{if ($driver->license->license_number != '')}
								{$driver->license->license_number}
							{else}
								<span class="text-muted">[не указано]</span>
							{/if}

							дата выдачи -
							{$driver->license->license_date} г.
							({$driver->license->license_full_years})
						</p>
					</div>
				</div>

				<div class="col-xs-12">
					<div class="form-group">
						{include "inc/car/kbm-label.tpl"}

						<p class="form-control-static">
							{if (isset($driver->license->kbm))}
								класс {$driver->license->kbm->title}
								(коэффициент - {$driver->license->kbm->coef})
							{else}
								<span class="text-muted">[не указан]</span>
							{/if}
						</p>
					</div>
				</div>
			</div>

		</div>
	</div>