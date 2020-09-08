<?php

require_once '../bootstrap.php';
 
$host=getenv("APP_URL");


$username= request()->get('username');
$password= request()->get('password');


$user=findUserByUsername($username);

if(empty($user)){
    $session->getFlashBag()->add("error","That user does not exist");
    redirect($host.'/');

}

if(password_verify($password,$user["password"])){
   
    $time= time()+36000;
    $jwt= Firebase\JWT\JWT::encode([
        "iss"=>request()->getBaseUrl(),
        "sub"=>$user['user_id'],
        "exp"=>$time,
        "iat"=>time(),
        "nbf"=>time(),
        "auth-username"=>$username
    ],
    getenv("SECRET_JWT"),
    "HS256");



    
    // $time= time()+36000;
    $cookie= createCookie($jwt,$time);

   



    $session->getFlashBag()->add("success","You are now logged in");
    
    redirect($host.'/',["cookies"=>[$cookie]]);
}
else{
    $session->getFlashBag()->add("error","Incorrect credentials");
    redirect($host.'/login.php');
    return false;
}

