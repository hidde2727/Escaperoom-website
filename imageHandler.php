<?php

//
//This file is used for retrieving the profile picture of an user and retrieving images from the file system
//We can't retrieve like normal (<img src="file location">) because we then need to expose the images to the outside world
//Exposing to the outside world in this context means that even if you aren't logged in you could retrieve the file
//By using this file we can make sure we don't need to expose the images because this file has access to the images and will copy them to the user
//

//start session
session_start();
//check if the sesion hasn't expired
if (array_key_exists('startTime', $_SESSION) && time() - $_SESSION['startTime'] > 144000) {
    //if session expired unset the session
    session_unset();
    session_destroy();
    //refresh the page for the user
    header('Location: /');
}

//check if the user is logged in on an account
if(array_key_exists('loggedIn', $_SESSION) && array_key_exists('username', $_SESSION)){
    //check if the file is a profile picture request (and the profile picture exists)
    if(array_key_exists('PROFILE', $_GET) && file_exists("users/" . $_SESSION['username'] . '/profilePicture.png')){
        //set the return type to an image
        header("Content-type: image/png");
        //return the profilePicture file
        readfile("users/" . $_SESSION['username'] . '/profilePicture.png');
    }
    //if the file isn't a profile picture request check if it is a image request (and the requested image exists)
    else if(array_key_exists('IMG', $_GET) && file_exists("users/" . $_SESSION['username'] . "/files" . $_GET['IMG'] . '.png')){ 
        //set the return type to an image
        header("Content-type: image/png");
        //return the requested image file in the file system
        readfile("users/" . $_SESSION['username'] . "/files" . $_GET['IMG'] . '.png');
    } 
}


?>