	<div class="form-group">
		<div class="input-group">
			<select
				class="form-control selectpicker"
				data-live-search="true"
				name="hospital_id"
			>
				<option class="text-muted" value="">-</option>
				{foreach $hospitals as $hospital}
					<option
						{if ($select_hospital_id == $hospital->id)}selected{/if}
						value="{$hospital->id}"
					>
						{$hospital->title}
					</option>
				{/foreach}
			</select>
			<span class="input-group-btn">
				<button class="btn btn-danger" sf-id="remove-hospital" title="Удалить" type="button">
					<span class="fa fa-times"></span>
				</button>
			</span>
		</div>
	</div>