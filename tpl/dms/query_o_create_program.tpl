{extends "classes/content_wide.tpl"}

{block "header_block"}{/block}
{block "header_title"}{$_PAGE->title}{/block}

{block "content_h1"}{/block}

{block "content" append}

	<div class="clearfix">
		{$_PAGE->content}
	</div>

	<form action="/{$_PAGE->name}/submit" class="form" id="create-program-form">
		<input name="clinics_selected" type="hidden">

		<section>
			<div class="panel panel-default">
				<div class="panel-body">
					<p>
						Выберите до 9 клиник из списка ниже.
						На следующем шаге Вы сможете выбрать оптимальную программу под свои запросы.
					</p>

					<h5>Выбранные клиники:</h5>

					<div class="margin-b">
						<ul id="chosen-clinics">
						</ul>
					</div>

					<button class="btn btn-success" id="create-program-next-btn" type="button">
						Далее
					</button>

					<ul hidden id="chosen-clinic-tpl">
						<li>
							<span sf-id="clinic-name"></span>
							<a class="text-danger" href="javascript:;" sf-id="chosen-clinic-remove" title="Удалить клинику из программы">
								<span class="fa fa-times"></span>
								Удалить
							</a>
						</li>
					</ul>

					<script>
						$('[sf-id="chosen-clinic-remove"]').click(function ()
						{
							var clinicId = $(this).closest('li').attr('sf-clinic-id');

							$('[sf-clinic-id="' + clinicId + '"][sf-id="clinic-remove-btn"]').click();
						});
					</script>
				</div>
			</div>
		</section>
	</form>

	<div class="row">

		<div class="col-sm-3 col-sm-push-9">

			<form action="/{$_PAGE->name}/submit" class="form" id="query-form">

				<div class="panel panel-default">
	
					<div class="panel-heading">
						<h5 class="panel-title">
							Параметры поиска
						</h5>
					</div>
	
					<ul class="list-group list-unstyled">

						<li class="list-group-item">
							{include "inc/dms/query_price_select.tpl"}
						</li>

						<li class="list-group-item sf-filter-group">
							{include "inc/dms/query_clinic_type.tpl"}
						</li>

						<li class="list-group-item sf-filter-group">
							{include "inc/dms/query_doctor_type.tpl"}
						</li>

						<li class="list-group-item sf-filter-group">
							{include "inc/dms/query_dentist_type.tpl"}
						</li>

						<li class="list-group-item sf-filter-group">
							{include "inc/dms/query_hospital_type.tpl" insurer_type=2}
						</li>

						<li class="list-group-item sf-filter-group">
							{include "inc/dms/query_ambulance_type.tpl"}
						</li>

						<li class="list-group-item">
							{include "inc/dms/query_staff_qty.tpl"}
						</li>

						<li class="list-group-item">
							{include "inc/dms/query_clinic_civil_type.tpl"}
						</li>

						<li class="list-group-item">
							<div class="margin-sm">
								<button class="btn btn-default btn-block" type="submit">Применить</button>
							</div>
						</li>

						<script>
							$(function ()
							{
								$('#query-form').submit(function ()
								{
									OpenUrl('/dms_query_o_create_program/?' + Serialize($('#query-form')));
									return false;
								});
							});
						</script>

					</ul>

				</div>{* .panel *}

			</form>

		</div>{* .col *}

		<div class="col-sm-9 col-sm-pull-3">

			{include "inc/dms/query_clinics_create_program.tpl"}

		</div>{* .col *}

	</div>{* .row *}

	{*
	<p class="text-muted">
		<small>
			* Мы стараемся поддерживать тарифы в актуальном состоянии,
			но в связи с частыми обновлениями и большим объёмом данных это непросто.
			Итоговая цена может немного отличаться как в большую, так и в меньшую сторону.
		</small>
	</p>
	*}

	<script>
		$(function ()
		{
			$('[data-toggle="popover"]').popover();

			DmsCreateProgramInitBtns(':not(#dms-clinics-map *)');

			$('#create-program-next-btn').click(function ()
			{
				$('[name="clinics_selected"]').val(DmsCreateProgramGetClinicsSelected());

				OpenUrl('/dms_query_o_create_program/program_selection?'
					+ Serialize($('#query-form'))
					+ '&' + Serialize($('#create-program-form')));
				return false;
			});
		});

		function DmsCreateProgramGetClinicsSelected()
		{
			var selected = [];

			$('#chosen-clinics [sf-clinic-id]').each(function ()
			{
				var $this = $(this);

				selected.push($this.attr('sf-clinic-id'));
			});

			return '[' + selected.join(',') + ']';
		}

		function DmsCreateProgramInitBtns(
			filter)
		{
			$('[sf-id="clinic-add-btn"]').filter(filter).on('click.sf', function ()
			{
				var $btn = $(this),
					clinicId = $btn.attr('sf-clinic-id'),
					$removeBtn = $('[sf-id="clinic-remove-btn"][sf-clinic-id="' + clinicId + '"]'),
					title = $('[sf-id="title"][sf-clinic-id="' + clinicId + '"]').text(),
					$clinicLi = $('#chosen-clinic-tpl').children().clone(true, true);

				$btn.hide();
				$removeBtn.show();

				$clinicLi.attr('sf-clinic-id', clinicId);
				$clinicLi.find('[sf-id="clinic-name"]').text(title);
				$('#chosen-clinics').append($clinicLi);
			});

			$('[sf-id="clinic-remove-btn"]').filter(filter).on('click.sf', function ()
			{
				var $btn = $(this),
					clinicId = $btn.attr('sf-clinic-id'),
					$addBtn = $('[sf-id="clinic-add-btn"][sf-clinic-id="' + clinicId + '"]'),
					$clinicLi = $('#chosen-clinics').find('li[sf-clinic-id="' + clinicId + '"]');

				$btn.hide();
				$addBtn.show();

				$clinicLi.remove();
			});
		}
	</script>

{/block}