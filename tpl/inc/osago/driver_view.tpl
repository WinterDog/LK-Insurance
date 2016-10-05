	<div class="panel panel-default">
		<div class="panel-heading">
			Водитель
		</div>

		<div class="panel-body padding-b-0">

			<div class="row">
				<div class="col-xs-12">
					<div class="form-group">
						<label class="control-label">Фамилия имя отчество, дата рождения</label>
						<p class="form-control-static">
							{if ($driver->fio != '')}
								{$driver->fio},
							{else}
								<span class="text-muted">[не указано]</span>,
							{/if}

							{$driver->birthday} г.
							(полных лет - {$driver->full_years})
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
								<span class="text-muted">[не указана]</span>
							{/if}

							№
							{if ($driver->license->license_number != '')}
								{$driver->license->license_number}
							{else}
								<span class="text-muted">[не указан]</span>
							{/if}

							дата выдачи -
							{$driver->license->license_date} г.
							(полных лет - {$driver->license->license_full_years})
						</p>
					</div>
				</div>

				<div class="col-xs-12">
					<div class="form-group">
						<label class="control-label">
							КБМ
							<a
								class="sf-tooltip"
								data-container="body"
								data-content="&quot;Коэффициент бонус-малус&quot; — один из показателей, влияющих на стоимость полиса ОСАГО.
									В зависимости от аварийности коэффициент может быть повышающим или понижающим."
								data-toggle="popover"
								data-trigger="focus"
								role="button"
								tabindex="0"
							>
								<span class="fa fa-question-circle"></span>
							</a>
						</label>
						<p class="form-control-static">
							{if ($driver->license->kbm)}
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