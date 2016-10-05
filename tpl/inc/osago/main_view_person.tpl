	{if ($person)}

		<div class="row">
	
			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label">Фамилия имя отчество, дата рождения</label>
					<p class="form-control-static">
						{$person->fio},
						{$person->birthday} г.
						(полных лет - {$person->full_years})
					</p>
				</div>
			</div>
	
			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label">Паспорт</label>
					<p class="form-control-static">
						серия {$person->passport->passport_series}
						№ {$person->passport->passport_number},
						выдан {$person->passport->passport_given},
						дата выдачи - {$person->passport->passport_date} г.
					</p>
				</div>
			</div>
	
			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label">Регистрация</label>
					<p class="form-control-static">
						{$person->passport->address_country},
						{if ($person->passport->address_index != '')}{$person->passport->address_index},{/if}
						{if ($person->passport->address_region != '')}{$person->passport->address_region},{/if}
						{$person->passport->address_city},
						{if ($person->passport->address_street != '')}{$person->passport->address_street},{/if}
						д. {$person->passport->address_house}{if ($person->passport->address_flat != '')}, кв. {$person->passport->address_flat}{/if}
					</p>
				</div>
			</div>
	
		</div>

	{else}

		<p class="text-muted">
			Нет данных.
		</p>

	{/if}