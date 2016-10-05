	<div id="uploader_div_tpl" style="display: none;">
		<ul class="uploader_list">
			<li class="uploader_item">
				{*
				Скрытый инпут с именем загруженного файла. Его значение будет передаваться при сабмите формы.
				<input type="hidden">
				*}

				{* Предпросмотр загруженного файла (сохранённого или только что загруженного). *}
				<div class="uploader_img"></div>

				<div class="uploader_btns">
					<div class="uploader_file">
						<a class="uploader_add" href="javascript:;">Загрузить</a>
						<a class="uploader_edit" href="javascript:;">Заменить</a>

						<input type="file">
					</div>

					<a class="uploader_delete" href="javascript:;">Удалить</a>

					<div class="fr_msg" style="display: none;">
						<div class="uploader_progressbar" style="display: none;"></div>
						<div class="uploader_msg"></div>
					</div>
				</div>
			</li>
		</ul>

		{* Кнопка загрузки очередного файла для вариантов с множеством файлов в одном поле. *}
		<div class="uploader_file">
			<a class="uploader_add" href="javascript:;">Загрузить</a>

			<input type="file">
		</div>
	</div>