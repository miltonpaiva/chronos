<?php

header ('Content-type: text/html; charset=UTF-8');

/**
 *
 */
class Activity
{
	private static $activity;
	private static $json_path;
	private static $date_now;

	function __construct()
	{
		global $include_paths, $entity;

		self::$date_now = date("d_m_Y");
		self::$json_path = "{$include_paths['json']}{$entity}/";

		self::setExistingActivity();
		self::setCurrentActivity($_REQUEST);
		self::saveActivity();
	}

	private static function setCurrentActivity($data_activity)
	{
		$key 			= $data_activity['k'];
		$time 			= $data_activity['t'];
		$running 		= $data_activity['r'];
		$description 	= $data_activity['d'];

		self::$activity[$key] =
			[
				'key'			=> $key,
				'time' 			=> $time,
				'running' 		=> $running,
				'description' 	=> $description
			];
	}

	private static function setExistingActivity()
	{
		$existing_data = self::selectCurrentArchives();

		self::$activity = $existing_data;
	}

	private static function selectCurrentArchives()
	{
		global $include_paths, $entity;
		$dir = dir(self::$json_path);

		while($archive = $dir->read()){
			$current_archive = strrpos($archive, self::$date_now);

				$path = "{$include_paths['json_web']}{$entity}/{$archive}";

			if ($current_archive > -1) {

				$data_archive = file_get_contents($path);

				$data_json = json_decode($data_archive, true);
				return $data_json;
	    	}
		}

	}

	private static function saveActivity()
	{

		$dir_valid = self::checkAndCreateDir();

		$json_created = false;

		if ($dir_valid) {
			$json_created = self::writeJson();
		}
	}

	private static function checkAndCreateDir()
	{
		$dir_exist = is_dir(self::$json_path);

		$dir_created = false;

		if (!$dir_exist) {
			$dir_created = mkdir($path, 0777, true);
		}

		$dir_valid = ($dir_exist || $dir_created);

		return $dir_valid;
	}

	private static function writeJson()
	{
		$data_activity = self::$activity;

		$data_json = json_encode($data_activity, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

		$date = self::$date_now;

		$path = self::$json_path;

		$json_name = "{$date}.json";

		$json_created = file_put_contents("{$path}{$json_name}", $data_json);

		return $json_created;
	}
}

new Activity();