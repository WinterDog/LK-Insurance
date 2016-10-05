<?php
    class ErrorsHandler
    {
		private $errors_stack = array();

		public function __construct()
		{
			return $this;
		}

		public function add(/*$error_text*/)
		{
			//$errors_stack[] = $error_text;
		}

		public function show()
		{
			if (!sizeof($this->errors_stack))
			{
				return;
			}

			echo implode('<br>', $this->errors_stack);
		}
    }
