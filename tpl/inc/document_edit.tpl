	<div
		class="panel panel-default margin-t"
		{if (isset($document))}
			id="document-{$document->id}"
		{/if}
		sf-id="document"
	>
		<div
			aria-expanded="true"
			class="panel-collapse collapse in"
		>
			<div class="panel-body">

				<form action="/{$_PAGE->name}/edit" class="form" sf-id="document-form">
					<input name="id" type="hidden" value="{$document->id|default}">
					<input name="user_id" type="hidden" value="{$user_id|default}">

					<div class="form-group">
						<label class="control-label">
							Название *
						</label>
						<input class="form-control" maxlength="256" name="title" type="text" value="{$document->title|default}">
						<span class="help-block">
							Укажите название для документа, чтобы потом отличить его.
						</span>
					</div>

					{if (isset($document))}
						<div class="form-group">
							<label class="control-label">
								Ссылка на документ
							</label>
							<p class="form-control-static">
								<a download="{$document->title}" href="/upload_m/d/{$document->file_path}" target="_blank">
									<span class="fa fa-download"></span>
									Скачать файл
								</a>
							</p>
							<span class="help-block">
								Если Вы загружаете новый документ, необходимо сохранить изменения, чтобы ссылка обновилась.
							</span>
						</div>
					{/if}

					<div class="form-group">
						<input jf-file-upload name="file_path" type="file" value="{$document->file_path|default}">
						<span class="help-block">
							Максимальный размер одного документа - 10 Мбайт.
							Допустимые форматы - JPG/JPEG, PNG, GIF, TIFF, DOC(X), XLS(X), RTF, PDF.
						</span>
					</div>

					<div>
						{if (isset($document))}
							<button type="button" class="btn btn-danger btn-sm" onclick="DocRemoveForm({$document->id});">
								<span class="fa fa-times"></span>
								Удалить документ
							</button>
						{else}
							<button type="button" class="btn btn-default btn-sm" onclick="DocCancel(this);">
								<span class="fa fa-undo"></span>
								Отмена
							</button>
						{/if}

						<button type="submit" class="btn btn-success btn-sm">
							<span class="fa fa-check"></span>
							Сохранить
						</button>
					</div>
				</form>

			</div>{* .panel-body *}
		</div>
	</div>