<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
/**
* @OA\Post(
*     path="/login",
*     description="Login to the system",
*     tags={"login"},
*     @OA\RequestBody(description="Basic user info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*    				@OA\Property(property="email", type="string", example="nermin.kapetanovic@stu.ibu.edu.ba",	description="Email"),
*    				@OA\Property(property="password", type="string", example="1234",	description="Password" )
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="JWT Token on successful response"
*     ),
*     @OA\Response(
*         response=404,
*         description="Wrong Password | User doesn't exist"
*     )
* )
*/
Flight::route('POST /login', function(){
    $login = Flight::request()->data->getData();
    $user = Flight::UserDao()->get_user_by_email($login['email']);
    if (isset($user['id'])){
        if($user['password'] == md5($login['password'])){
            unset($user['password']);
            $jwt = JWT::encode($user, Config::JWT_SECRET(), 'HS256');
            Flight::json(['token' => $jwt]);
        }else{
            Flight::json(["message" => "Wrong password"], 404);
        }
    }else{
        Flight::json(["message" => "User doesn't exist"], 404);
    }
});
/**
* @OA\Post(
*     path="/register",
*     description="User registration",
*     tags={"register"},
*     @OA\RequestBody(description="Registration of an user", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*    				@OA\Property(property="email", type="string", example="nermin.kapetanovic@stu.ibu.edu.ba",	description="Email"),
*    				@OA\Property(property="password", type="string", example="1234",	description="Password" )
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="JWT Token on successful response"
*     ),
*     @OA\Response(
*         response=404,
*         description="Wrong Password | User doesn't exist"
*     )
* )
*/
Flight::route('POST /register', function(){
    Flight::json(Flight::UserService()->register(Flight::request()->data->getData()));
});

?>