<?php

/**
 * IncludeController
 */
class IncludeController
{
	private $projectPath;
	private $urlWeb;
	private $paths = array();

	function __construct()
	{
		global $project_path, $url_web, $include_paths;

		$this->projectPath 	= $project_path;
		$this->urlWeb 		= $url_web;
		$this->paths 		= $include_paths;
	}

	public function page($entity = 'login')
	{
		$page = $this->montPath($entity);
		if (file_exists($page)) {
			include $page;
		}else{
			include $this->paths['pages'] . '404.php';
		}
	}

	private function montPath($page_name)
	{
		return $this->paths['pages'] . "{$page_name}.php";
	}

	public function archive($file)
	{
		if (strpos($file, 'css')) {
			$tag = "<link href='{$this->paths['assets']}{$file}' rel='stylesheet'> \n";
		}
		if (strpos($file, 'js')) {
			$tag = "<script type='text/javascript' src='{$this->paths['assets']}{$file}'></script> \n";
		}

		echo $tag;
	}

	public function img($file)
	{
		echo "{$this->paths['assets']}{$file}";
	}

	public function parts($file)
	{
		include "{$this->paths['parts']}{$file}";
	}

	public function models($model)
	{
		$model = "{$this->paths['models']}{$model}.php";
		if (file_exists($model)) {
			include $model;
		}else{
			include $this->paths['pages'] . '404.php';
		}
	}
}


 ?>