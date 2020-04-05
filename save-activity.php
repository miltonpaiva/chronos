<?php

header ('Content-type: text/html; charset=UTF-8');

/**
 *
 */
class Activity
{
	private static $activity;
	private static $project_root;

	function __construct()
	{
		self::setActivity($_REQUEST);
		self::saveActivity();
	}

	private static function setActivity($data_activity)
	{
		$key 			= $data_activity['k'];
		$time 			= $data_activity['t'];
		$running 		= $data_activity['r'];
		$description 	= $data_activity['d'];

		self::$activity =
			[
				'key'			=> $key,
				'time' 			=> $time,
				'running' 		=> $running,
				'description' 	=> $description
			];
	}

	private static function saveActivity()
	{
		$data_activity = self::$activity;

		$project_path = current(explode('index.php', $_SERVER['SCRIPT_NAME']));

		self::$project_root = $_SERVER['DOCUMENT_ROOT'];
		echo "<pre>";
		print_r($_SERVER);
		exit();


	}
}

new Activity();