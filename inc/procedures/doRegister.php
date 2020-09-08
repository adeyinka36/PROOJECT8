<?php

require_once '../bootstrap.php';
 
$host=getenv("APP_URL");


$username= request()->get('username');
$password= request()->get('password');
$confirmPassord=request()->get('confirm_password');

if($password!=$confirmPassord){
    $session->getFlashBag()->add("error","Passwords do not match");
    redirect($host.'/register.php');

}



$user= findUserByUsername($username);


if(!empty($user)){
    $session->getFlashBag()->add("error","This user already exists");
    redirect($host.'/register.php');
    
}

$hash= password_hash($password,PASSWORD_DEFAULT);
$user=createUser($username,$hash);
$session->getFlashBag()->add("sucess","User is created");

//auto-login 
   $newUser= findUserByUsername($username);
  
   $time= time()+36000;
   $jwt= Firebase\JWT\JWT::encode([
       "iss"=>request()->getBaseUrl(),
       "sub"=>$newUser['user_id'],
       "exp"=>$time,
       "iat"=>time(),
       "nbf"=>time(),
       "auth-username"=>$username
   ],
   getenv("SECRET_JWT"),
   "HS256");
  

   
   
   $cookie= createCookie($jwt,$time);


    // $data=[
    //     "auth-username"=>$newUser["username"],
    //     "auth-userid"=>$newUser["user_id"]
    // ];


    
    // $time= time()+36000;
    // $cookie= createCookie(json_encode($data),$time);

   



    $session->getFlashBag()->add("success","You are now logged in");
    
    redirect($host.'/',["cookies"=>[$cookie]]);

