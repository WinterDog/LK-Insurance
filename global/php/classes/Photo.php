<?php
	namespace sf;

	/*
		Класс для работы с изображениями.
	*/
	class Photo
	{
		public static $folder = 'upload_m/';
		public static $folder_temp = 'upload_m/temp/';
		public static $folder_admin_images = 'upload_m/a/';
		public static $folder_docs = 'upload_m/d/';

		/*
			Сохранение пришедших файлов во временную папку.
		*/
		public static function CreateTemp(
			$file,
			$allowed_extensions = ['gif', 'jpg', 'jpeg', 'png'],
			$filename_prefix = '')
		{
			if (!is_array($file))
				die('Файлы не были переданы.');

			if ($file['size'] > 10000000)
				die('Слишком большой файл. Максимальный допустимый размер файла - 10 Мбайт.');

			$path_info = pathinfo($file['name']);
			$path_info['extension'] = strtolower($path_info['extension']);

			if (!in_array($path_info['extension'], $allowed_extensions))
				die('Неподдерживаемый формат файла. Допускаются файлы следующих типов: '.implode(', ', $allowed_extensions).'.');

			$new_filename = $filename_prefix.md5(md5_file($file['tmp_name']).time()).'.'.$path_info['extension'];

			move_uploaded_file($file['tmp_name'], self::$folder_temp.$new_filename);
			chmod(self::$folder_temp.$new_filename, 0766);

			return $new_filename;
		}

		/*
			Сохранение файла из временной папки в постоянную.
		*/
		public static function SaveImage(
			$filename,
			$size,
			$thumbSize,
			$folder)
		{
			if (file_exists($folder.$filename))
				return true;

			if (!file_exists(self::$folder_temp.$filename))
			{
				debug_log_message('Error : No file with name '.$filename.' found in temp upload folder.');
				return null;
			}

			// Copy source image.
			rename(self::$folder_temp.$filename, $folder.'src/'.$filename);

			// Resize and copy image to the main folder.
			$thumb = \PhpThumbFactory::create($folder.'src/'.$filename);
			$thumb->adaptiveResize($size[0], $size[1])->save($folder.$filename);

			// Create thumb in the thumb folder.
			$thumb = \PhpThumbFactory::create($folder.'src/'.$filename);
			$thumb->adaptiveResize($thumbSize[0], $thumbSize[1])->save($folder.'thumbs/'.$filename);

			return true;
		}

		/*
			Сохранение файла из временной папки в постоянную.
		*/
		public static function SaveFile(
			$filename,
			$folder)
		{
			if (file_exists($folder.$filename))
				return true;

			if (!file_exists(self::$folder_temp.$filename))
			{
				debug_log_message('Error : No file with name '.$filename.' found in temp upload folder.');
				return null;
			}

			// Copy source image.
			rename(self::$folder_temp.$filename, $folder.$filename);

			return true;
		}

		/*
			Удаление файла из постоянной папки.
		*/
		public static function Remove(
			$filename,
			$folder)
		{
			if (!file_exists($folder.$filename))
				return null;

			unlink($folder.$filename);

			if (file_exists($folder.'thumbs/'.$filename))
				unlink($folder.'thumbs/'.$filename);

			if (file_exists($folder.'src/'.$filename))
				unlink($folder.'src/'.$filename);

			return true;
		}

		/*
			Очистка временной папки от старых файлов, которые не были сохранены в постоянную папку.
		*/
		public static function ClearTempFolder()
		{
			//
		}
	}
?>