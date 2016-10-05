	<div id="drivers-div-short" style="display: none;">
		<input name="drivers" type="hidden">

		<div wd-id="drivers-list-div">
			{include "inc/driver/driver_short.tpl"}
		</div>

		<div class="form-group margin-b">
			{* Button hides when there are 5 drivers in the list. *}
			<button class="btn btn-primary btn-sm" type="button" wd-id="add-driver-btn" onclick="PolicyAddDriverShort();">
            	<span class="fa fa-plus"></span>
				Добавить водителя
			</button>
		</div>

		{include "inc/driver/driver_short.tpl" tpl=true}
	</div>