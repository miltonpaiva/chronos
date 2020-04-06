<?php

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  date_default_timezone_set('America/Sao_Paulo');
  header ('Content-type: text/html; charset=UTF-8');
  /**
   * [$project_path Contem o path padrão da aplicação]
   * @var [string]
   */
  $project_path = current(explode('index.php', $_SERVER['SCRIPT_NAME']));

  /**
   * [$project_name Nome da aplicação]
   * @var [string]
   */
  $project_name = 'Chonos';

  session_start();

  /**
   * [$url_web url base do projeto]
   * @var [string]
   */
  isset($_SERVER['HTTPS']) ? $host = 'https' : $host = 'http';
  $url_web = "{$host}://{$_SERVER['HTTP_HOST']}{$project_path}";
 // echo "<pre>";
 // print_r($_SERVER);
 // exit;

  /**
   * [$include_paths paths base do projeto]
   * @var [array]
   */
  $include_paths['absolut']     =   $_SERVER['DOCUMENT_ROOT'];
  $include_paths['models']      =   "{$include_paths['absolut']}{$project_path}app/model/";
  $include_paths['pages']       =   "{$include_paths['absolut']}{$project_path}app/view/";
  $include_paths['parts']       =   "{$include_paths['absolut']}{$project_path}parts/";
  $include_paths['json']        =   "{$include_paths['absolut']}{$project_path}data/json/";
  $include_paths['assets']      =   "{$url_web}assets/";
  $include_paths['json_web']    =   "{$url_web}data/json/";

  require_once $_SERVER['DOCUMENT_ROOT'] . "{$project_path}app/Controller.php";

  /**
   * [Instancia da Classe Controller]
   * @var Controller
   */
  new Controller();

  /**
   * @param  [String]
   * @return [Array]
   */
  function formatPath($path_info)
  {
    $data = explode('/', $path_info);
    foreach ($data as $key => $path) {
      if ($path == "") {
        unset($data[$key]);
      }
    }
    return $data;
  }

  /**
   * [$path_info define as ações na aplicação]
   * @var [Array]
   */
  $path_info = formatPath(@$_SERVER['PATH_INFO']);

  $entity;

  if (count($path_info) == 1) {

    $entity = $path_info[1];

    Controller::includePage('activity');
  }elseif (count($path_info) > 1){

    $entity = $path_info[1];
    $model = $path_info[2];

    Controller::includeModel($model, $entity);
  }else{
    Controller::includePage('home');
  }
