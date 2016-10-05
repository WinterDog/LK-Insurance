<?php
	class Response extends BaseObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'email'		=> 'email',
				'message'	=> 'text',
				'name'		=> 'string',
				'phone'		=> 'string',
			));

			if (!$data['name'])
				$errors[] = 'Пожалуйста, укажите, как к вам обращаться.';
			if (!$data['email'])
				$errors[] = 'Пожалуйста, укажите электронный адрес, чтобы мы могли ответить вам.';
			if (!$data['message'])
				$errors[] = 'Текст сообщения пуст.';

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		public function send()
		{
			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/manager_response.tpl');
			$tpl->assign(array
			(
				'_CFG'			=> &$GLOBALS['_CFG'],
				'response'		=> &$this,
			));
			$text = $tpl->fetch();

			sf\send_mail(
				$GLOBALS['_CFG']['email']['info'],
				'Через форму обратной связи отправлено сообщение',
				$text,
				$this->email,
				$this->name);

			return $this;
		}
	}
?>