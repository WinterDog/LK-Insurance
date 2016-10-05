	<div class="modal fade" id="ref-modal" tabindex="-1">
		<input id="ref-item-id" type="hidden" value="">

		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button aria-label="Закрыть" class="close" data-dismiss="modal" type="button">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Удаление записи</h4>
				</div>
				<div class="modal-body">
					<p>
						Вы уверены, что хотите удалить запись?
					</p>
				</div>
				<div class="modal-footer">
					<button class="btn btn-default" data-dismiss="modal" type="button">Отмена</button>
					<button class="btn btn-primary" type="button" onclick="RefItemDelete();">Да</button>
				</div>
			</div>
		</div>
	</div>

	<script>
		function RefItemDeleteForm(
			id)
		{
			$('#ref-item-id').val(id);
			$('#ref-modal').modal('show');
		}

		function RefItemDelete()
		{
			BlockUI();

			$.ajax(
			{
				url:		'/{$page_name}/delete?id=' + $('#ref-item-id').val(),
				success:	function (a, b, xhr)
				{
					// TODO: Here we firstly wait Ajax query to finish, then until the modal is hidden.
					// It is very bad. :-\
					$('#ref-modal').modal('hide');

					if (!xhr.getResponseHeader('Result'))
					{
						UnblockUI();
						return;
					}
					$('#ref-modal').on('hidden.bs.modal', function ()
					{
						OpenUrl();
					});
				},
			});
		}
	</script>