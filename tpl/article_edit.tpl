{extends "classes/content.tpl"}

{block "content_title"}{if (!isset($article))}Добавление{else}Редактирование{/if} статьи{/block}

{block "content" append}

	<script src="/lib/ckeditor-4.5.7/ckeditor.js"></script>
	<script src="/lib/ckeditor-4.5.7/adapters/jquery.js"></script>

	<form action="/{$_PAGE->name}/edit" class="form" id="article_form">
		<input name="id" type="hidden" value="{$article->id|default}">

		<div class="form-group">
			<label class="control-label">Тип статьи *</label>
			<select class="form-control" name="article_type_id">
				<option value="">-</option>
				{foreach $article_types as $article_type}
					<option
						{if ((isset($article)) && ($article->article_type_id == $article_type->id))}selected{/if}
						value="{$article_type->id}"
					>
						{$article_type->title}
					</option>
				{/foreach}
			</select>
		</div>

		<div class="form-group">
			<label class="control-label">Заголовок *</label>
			<input class="form-control" name="title" type="text" value="{$article->title|default}">
		</div>

		<div class="form-group">
			<label class="control-label">Краткий текст *</label>
			<textarea class="form-control" name="content_cut" rows="3">{$article->content_cut|default}</textarea>
			<span class="help-block">
				Напишите сюда краткое содержание статьи (1-2 предложения).
			</span>
		</div>

		<div class="form-group">
			<label class="control-label">Текст *</label>
			<textarea ckeditor name="content" rows="20">{$article->content|default}</textarea>
			<span class="help-block">
				Пожалуйста, не ставьте лишних пустых строк в начале и конце текста, а также между абзацами -
				текст будет выровнен в соответствии с общим стилем сайта.
			</span>
		</div>

		<div class="form-group">
			<label class="control-label">Главная картинка</label>
			<div>

				<div class="img-thumbnail" image_preview onclick="OpenKCFinder(this);">
					<input name="main_image" type="hidden" value="{$article->main_image|default}">

					<div class="image-preview">
						<div class="text-wrap" {if ((isset($article)) && ($article->main_image != ''))}style="display: none;"{/if}>
							Нажмите сюда, чтобы выбрать изображение.
						</div>

						{if ((isset($article)) && ($article->main_image != ''))}
							<img alt="{$article->title}" class="img-responsive" src="{$article->main_image}">
						{/if}
					</div>
				</div>

				<span class="help-block">
					Выберите главную картинку для новости. Она будет показываться в списке новостей.
					Можно выбрать существующую или загрузить новую картинку (кнопка Upload сверху в окне выбора).
				</span>

				<script>
					function OpenKCFinder(
						div)
					{
						var $image_preview = $(div);

						window.KCFinder =
						{
							callBack: function (url)
							{
								BlockUI($image_preview);

								$image_preview.find('input[name]').val(url);

								window.KCFinder = null;
								//$('#image_preview').html('<div class="margin-sm">Загрузка...</div>');

								var img = new Image();

								img.src = url;
								img.onload = function ()
								{
									$image_preview.find('img').remove();

									var $img = $('<img src="' + url + '">');

									$img.addClass('img-responsive');
									$image_preview.find('.image-preview').append($img);
									$image_preview.find('.text-wrap').hide();

									/*
									var img_width = $img.outerWidth(),
										img_height = $img.outerHeight(),
										div_width = $image_preview.outerWidth(),
										div_height = $image_preview.outerHeight();

									if ((img_width > div_width) || (img_height > div_height))
									{
										if ((div_width / div_height) > (img_width / img_height))
											div_width = parseInt((img_width * div_height) / img_height);
										else if ((div_width / div_height) < (img_width / img_height))
											div_height = parseInt((img_height * div_width) / img_width);

										$img.css(
										{
											'width':	div_width + 'px',
											'height':	div_height + 'px',
										});
									}
									else
									{
										div_width = img_width;
										div_height = img_height;
									}

									$img.css(
									{
										'margin-left':	parseInt(($image_preview.outerWidth() - div_width) / 2) + 'px',
										'margin-top':	parseInt(($image_preview.outerHeight() - div_height) / 2) + 'px',
									});
									*/

									UnblockUI($image_preview);
								};
							},
						};
						window.open('/lib/kcfinder/browse.php?type=images&dir=images/public',
							'kcfinder_image', 'status=0, toolbar=0, location=0, menubar=0, ' +
							'directories=0, resizable=1, scrollbars=0, width=900, height=500');
					};
				</script>

			</div>
		</div>

		<div class="form-group">
			<label class="control-label">Источник (название)</label>
			<input class="form-control" name="source_title" type="text" value="{$article->source_title|default}">
			<span class="help-block">
				Если текст статьи скопирован из другого ресурса (сайта, журнала и т. п.), укажите здесь его название.
			</span>
		</div>

		<div class="form-group">
			<label class="control-label">Источник (ссылка)</label>
			<input class="form-control" name="source_url" type="text" value="{$article->source_url|default}">
			<span class="help-block">
				Если текст статьи скопирован с конкретной страницы, укажите здесь ссылку на неё.
			</span>
		</div>

		<div class="text-center">
			<button class="btn btn-default" type="button" onclick="GoBack();">Отмена</button>
			<button class="btn btn-success" type="submit">{if (!isset($article))}Добавить{else}Сохранить{/if}</button>
		</div>
	</form>

	<script>
		$(function ()
		{
			init_rich_text_editors();

			$('#article_form').submit(function ()
			{
				submit_data(this,
				{
					success: function (xhr)
					{
						OpenUrl(xhr.responseText);
					},
				});
				return false;
			});
		});
	</script>

{/block}