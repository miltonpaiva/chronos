<?php

	global $project_path;
	require_once $_SERVER['DOCUMENT_ROOT'] . "{$project_path}app/controller/IncludeController.php";

/**
 * Controller
 */
class Controller
{
	private static $include;
	private static $entity;

	public static $DB;

	function __construct()
	{
		self::$include = new IncludeController();
	}

	public static function includePage($entity = '')
	{
		self::$include->Page($entity);
	}

	public static function includeArchive($file)
	{
		self::$include->Archive($file);
	}

	public static function includeParts($file)
	{
		self::$include->parts($file);
	}

	public static function includeImg($file)
	{
		self::$include->img($file);
	}

	public static function includeModel($model, $entity, $args = '')
	{
		self::$include->models($model);
	}

}

?>