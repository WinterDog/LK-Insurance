<?php
	class CallbackRequest extends BaseObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'name'		=> 'string',
				'phone'		=> 'string',
			));

			if (!$data['name'])
				$errors[] = 'Пожалуйста, укажите, как к вам обращаться.';
			if (!$data['phone'])
				$errors[] = 'Пожалуйста, укажите электронный адрес, чтобы мы могли ответить вам.';

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		public function send()
		{
			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/manager_callback.tpl');
			$tpl->assign(array
			(
				'_CFG'			=> &$GLOBALS['_CFG'],
				'request'		=> &$this,
			));
			$text = $tpl->fetch();

			sf\send_mail(
				$GLOBALS['_CFG']['email']['info'],
				'СРОЧНО - Посетитель сайта заказал звонок',
				$text);

			return $this;
		}
	}
?>