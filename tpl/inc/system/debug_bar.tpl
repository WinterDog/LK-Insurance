	<script>
		$(function ()
		{
			$('body>.debug-bar').remove();
		});
	</script>

	{if ((isset($_DEBUG_MESSAGES)) && (sizeof($_DEBUG_MESSAGES) > 0))}
		<div class="container-fluid debug-bar margin-t-lg margin-lr-lg">
			<h4>Debug info</h4>
			{foreach from=$_DEBUG_MESSAGES item=item}
				<div>{$item}</div>
			{/foreach}
		</div>
	{/if}

	<script>
		$(function ()
		{
			$('.debug-bar').insertAfter('.body-wrap');
		});
	</script>