	{* foreach $msg_text as $msg}
		<strong>{$msg.head}</strong>
		<p>{$msg.text}</p>
	{/foreach *}

	<ul>
		{foreach $msg_err as $key => $msg}
			<li err_key="{$key}">{$msg}</li>
		{/foreach}
	</ul>