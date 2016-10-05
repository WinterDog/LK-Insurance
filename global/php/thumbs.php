<?php
/*
#############################################################
#                                                           #
#  Class: Thumbs                                            #
#  parameters for the initialization (2):                   #
#     $dir: relative path to the pictures                   #
#     $tPrefix: will be set infront of each thumb-filename  #
#     $maxW: max. width of the thumbs (no matter if fix=2)  #
#     $maxH: max. height of the thumbs (no matter if fix=1) #
#     $fix: primary size-limit: 1=width, 2=height, 0=both   #
#                                                           #
#  Code by BladeX (ilja_m@gmx.de)                           #
#  http://www.gwod.de.vu                                    #
#                                                           #
#  Using this class is for free, while you use it           #
#  non-commercial!                                          #
#                                                           #
#  Removing this note is NOT allowed!                       #
#                                                           #
#############################################################
*/

	// Функция вынимает расширение файла
	function get_fileext($filepath)
	{
		$last_point = strrpos($filepath, '.');
		if ($last_point === false)
			return false;
		return strtolower(substr($filepath, $last_point + 1));
	}

	class Thumbs
	{
		private $dir = '';
		private $thumbDir = '';
		private $tPrefix = '';
		private $maxH = 0;
		private $maxW = 0;
		private $fix = 0;

	    // initialize the class
		function Thumbs($dir, $tPrefix = '', $maxW = 150, $maxH = 150, $fix = 0)
		{
			if (substr($dir, -1) == '/')
				$dir = substr($dir, 0, -1);
			$this->dir = $dir;
			$this->tPrefix = $tPrefix;
			$this->thumbDir = $dir;
			$this->maxH = $maxH;
			$this->maxW = $maxW;
			$this->fix = $fix;
			if (!file_exists($this->dir))
				die('Path \"'.$this->dir.'\" doesn\'t exist! Please set the right path to your picture folder.');
			if (!file_exists($this->thumbDir))
				die('Path \"'.$this->thumbDir.'\" doesn\'t exist! Please create the subdir \"/thumbs\" in your picture folder.');
			if (!is_writable($this->thumbDir))
				die('Path \"'.$this->thumbDir.'\" has no write rights! Please set the rights to 777 via chmod() first.');
		}

		// find images in given directory
		function getImageNames()
		{
			$files = false;
			if ($resDir = opendir($this->dir))
			{
				// check all files in $dir - add images to array 'files'
				$cpos = 0;
				while ($file = readdir($resDir))
				{
					if ($file[0] != '_' && $file != '.' && $file != '..' && !is_dir($this->dir . '/' . $file))
					{
						//$ext = substr($file, -3);
						$ext = pathinfo($file, PATHINFO_EXTENSION);
						$ext = strtolower($ext);
						if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'png')
						{
							$files[$cpos] = $file;
							$cpos++;
						}
					}
				}
				closedir($resDir);
			}
			if ($files)
				sort($files);

			return $files;
		}

	    // check whether a thumb was allready created
		function checkThumb($image)
		{
			$thumbFile = $this->thumbDir.'/'.$this->tPrefix.$image;
			if (file_exists($thumbFile))
			{
				list($srcW, $srcH, $srcType, $html_attr) = getimagesize($thumbFile);
				if ($this->fix == 1)
				{
					if($this->maxW != $srcW)
						return false;
				}
				elseif ($this->fix == 2)
				{
					if ($this->maxH != $srcH)
						return false;
				}
				else
				{
					if ($srcH > $this->maxH || ($srcW != $this->maxW && $srcH != $this->maxH))
						return false;
				}
				//echo "thumb of $image exists<br>";
				return true;
			}
			else
			{
				//echo "thumb of $image doesn't exist!!!<br>";
				return false;
			}
		}

	    // create a new thumb to given image
		function createThumb($image)
		{
			$srcFile = $this->dir.'/'.$image;
			list($srcW, $srcH, $srcType, $html_attr) = getimagesize($srcFile);
			//$ext = substr($image, -3);
			$ext = pathinfo($image, PATHINFO_EXTENSION);
			$ext = strtolower($ext);
			if (($ext == 'jpg') || ($ext == 'jpeg'))
				$srcImage = @imagecreatefromjpeg($srcFile);
			elseif ($ext == 'gif')
				$srcImage = @imagecreatefromgif($srcFile);
			elseif ($ext == 'png')
				$srcImage = @imagecreatefrompng($srcFile);

			if (!$srcImage) return false;
			//$srcW = imagesx($srcImage);
			//$srcH = imagesy($srcImage);
			if ($this->fix == 0)
			{
				if ($srcW / $this->maxW > $srcH / $this->maxH)
					$factor = $this->maxW / $srcW;
				else
					$factor = $this->maxH / $srcH;
			}
			elseif ($this->fix == 1)
				$factor = $this->maxW / $srcW;
			elseif ($this->fix == 2)
				$factor = $this->maxH / $srcH;

			$newH = (int)round($srcH * $factor);
			$newW = (int)round($srcW * $factor);

			$newImage = imagecreatetruecolor($newW, $newH);
			imagecopyresampled($newImage, $srcImage, 0, 0, 0, 0, $newW, $newH, $srcW, $srcH);
			$newFile = $this->thumbDir.'/'.$this->tPrefix.$image;
			imagejpeg($newImage, $newFile, '85');
			return true;
		}

	    // collect all images, creates thumbs and return all as an array: return[0] = images_array, return[1] = thumbs_array
		function getImages()
		{
			@set_time_limit(300);
			$images = '';
			$thumbs = '';
			$cpos = 0;
			$imageList = $this->getImageNames();

			//echo "<div id=\"wait\" style=\"display: \" align=\"center\"><font color=\"#FF0000\">Please wait...</font></div>\n";
			//flush();
			//ob_flush();
			foreach($imageList as $image)
			{
				$thumb = false;
				$thumb = $this->checkThumb($image);
				if (!$thumb)
				{
					$thumb = $this->createThumb($image);
					//if(!$thumb) echo "$image is not a valid image<br>";
				}
				if ($thumb)
				{
					$images[$cpos] = $this->dir.'/'.$image;
					$thumbs[$cpos] = $this->thumbDir.'/'.$this->tPrefix.$image;
					$cpos++;
				}
			}
			//echo "<script type=\"text/javascript\">document.all.wait.style.display = 'none';</script>\n";

			return array($images, $thumbs);
		}

	    // rebuild ALL thumbs - may be usefull for Admins, if the content of an image has changed
		function rebuildThumbs()
		{
			@set_time_limit(300);
			$imageList = $this->getImageNames();
			foreach($imageList as $image)
				$this->createThumb($image);
		}
	}
?>