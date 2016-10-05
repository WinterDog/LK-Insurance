	<div
		affiliate
		class="panel panel-default"
		{if (isset($affiliate))}
			id="affiliate-{$affiliate->id}"
		{else}
			id="affiliate_tpl"
			style="display: none;"
		{/if}
	>
		<input name="affiliate_id" type="hidden" value="{$affiliate->id|default}">

		<div class="panel-body">

			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Адрес *</label>
						<input class="form-control" name="address" type="text" value="{$affiliate->address|default}">
					</div>

					<div class="form-group">
						<label class="control-label">Станция метро</label>
						<select
							class="form-control"
							name="metro_station_id"
						>
							<option class="text-muted" value="">-</option>
							{foreach $metro_stations as $item}
								<option
									value="{$item->id}"
									{if ((isset($affiliate)) && ($affiliate->metro_station_id == $item->id))}
										selected
									{/if}
								>
									{$item->title}
								</option>
							{/foreach}
						</select>
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Внутренний комментарий</label>
						<textarea class="form-control" name="note" rows="3">{$affiliate->note|strip_tags:false|default}</textarea>
						<span class="help-block">Будет виден только администраторам.</span>
					</div>
				</div>
			</div>

			<div class="panel panel-default margin-t">
				<div class="panel-heading">
					<h5 class="panel-title">
						<a
							data-toggle="collapse"
							href=""
						>
							Фотографии
						</a>
					</h5>
				</div>
		
				<div
					aria-expanded="{if (isset($affiliate))}false{else}true{/if}"
					class="panel-collapse collapse {if (!isset($affiliate))}in{/if}"
					id=""
				>
					<div class="panel-body">

						<div class="form-group">
							<input jf-file-upload multiple type="file">
							<span class="help-block">
								Максимальный размер одной фотографии - 10 Мбайт.
								Допустимые форматы - JPG/JPEG, PNG, GIF.
								Максимальное количество фотографий для одного отделения - 10.
								Загруженные фотографии можно менять местами (перетаскиванием).
							</span>
						</div>

					</div>{* .panel-body *}
				</div>
			</div>

			<div class="margin-t">
				<button class="btn btn-danger" type="button" onclick="RemoveAffiliate(this);">
					<span class="fa fa-times"></span>
					Удалить отделение
				</button>
			</div>

		</div>
	</div>

	<script>
		$(function ()
		{
			{if (isset($affiliate))}

				InitFileUpload(
					$('#affiliate-{$affiliate->id}'),
					[
						{foreach $affiliate->photos as $photo}
							'<img alt="Фотография" class="file-preview-image" jf-filename="{$photo['src']}" src="/upload_m/a/thumbs/{$photo['src']}" title="Фотография">',
						{/foreach}
					],
					{
						extensions:		[ 'gif', 'jpg', 'jpeg', 'png', ],
						maxFileCount:	10,
						showPreview:	true,
						url:			'/upload_file_admin/',
					});

				DmsInitAffiliateCollapse(
					$('#affiliate-{$affiliate->id}'));

			{/if}
		});
	</script>