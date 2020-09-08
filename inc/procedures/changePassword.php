<?php

require_once '../bootstrap.php';

$host=getenv("APP_URL");

$password=request()->get("current_password");
$newPassword=request()->get("password");
$confirmPassword=request()->get("confirm_password");

if($newPassword!=$confirmPassword){
    
    $session->getFlashBag()->add("error","Paswords do not match");
    redirect($host."/account.php");
    return false;
}

changePassword($password,$newPassword);





