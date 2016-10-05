<?php
	include LIB.'ckeditor/ckeditor.php';
	include LIB.'ckfinder/ckfinder.php';

	$ckeditor = new CKEditor();
	$ckeditor->basePath = LIB.'ckeditor/';
	CKFinder::SetupCKEditor($ckeditor, LIB.'ckfinder/');
	$ckeditor->editor('CKEditor1');
?>