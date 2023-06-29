<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/services/UserService.php';
require_once __DIR__.'/services/ProjectService.php';
require_once __DIR__.'/dao/UserDao.php';
require_once __DIR__.'/dao/ProjectDao.php';


Flight::register('UserDao', 'UserDao');
Flight::register('ProjectDao', 'ProjectDao');
Flight::register('UserService', 'UserService');
Flight::register('ProjectService', 'ProjectService');



Flight::map('error', function(Exception $ex){
    // Handle error
    Flight::json(['message' => $ex->getMessage()], 500);
});

/* utility function for reading query parameters from URL */
Flight::map('query', function($name, $default_value = NULL){
  $request = Flight::request();
  $query_param = @$request->query->getData()[$name];
  $query_param = $query_param ? $query_param : $default_value;
  return urldecode($query_param);
});

// middleware method for login
Flight::route('/*', function(){
  //return TRUE;
  //perform JWT decode
  $path = Flight::request()->url;
  #print_r($path); die; 
  if ($path == '/login' || $path == '/docs.json' || $path == '/register') return TRUE; // exclude login route from middleware

  $headers = getallheaders();
  if (@!$headers['Authorization']){
    Flight::json(["message" => "Authorization is missing"], 403);
    return FALSE;
  }else{
    try {
      $decoded = (array)JWT::decode($headers['Authorization'], new Key(Config::JWT_SECRET(), 'HS256'));
      Flight::set('user', $decoded);
      return TRUE;
    } catch (\Exception $e) {
      Flight::json(["message" => "Authorization token is not valid"], 403);
      return FALSE;
    }
  }
});


require_once __DIR__.'/routes/UserRoute.php';
require_once __DIR__.'/routes/ProjectRoute.php';


Flight::start();
?>