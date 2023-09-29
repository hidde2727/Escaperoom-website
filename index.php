<!DOCTYPE html>

<head>
<title>Shmel</title>

<!-- icons -->
<link rel="stylesheet" href="/media/font-awesome-4.7.0/css/font-awesome.min.css">

</head>



<?php

//addSaveButton is needed for the editing overlay, if set to true the editing overlay will add an extra save button
$addSaveButton = FALSE;

//explode the URL
$GLOBALS['URLExploded'] = explode('/', $_SERVER['REQUEST_URI']);
//start session
session_start();
//check if the session isn't expired
if (array_key_exists('startTime', $_SESSION) && time() - $_SESSION['startTime'] > 144000) {
    //if session expired unset the session
    session_unset();
    session_destroy();
    //refresh the page for the user
    header('Location: /');
}

//make sure the editing variable is set to true if the user is currently editing
if(array_key_exists('editing', $_SESSION) && $_SESSION['editing'] === TRUE){
    $editing = TRUE;
}
else {
    $editing = FALSE;
}

//make sure the admin variable is set to true if the user is currently logged in as admin
if(array_key_exists('admin', $_SESSION) && $_SESSION['admin'] === TRUE){
    $admin = TRUE;
}
else {
    $admin = FALSE;
}

//include the mysql handler
include 'mysql/handler.php';
// Create connection
$connection = new MySQLHandler();

?>

<style>

  html{
    scroll-behavior:smooth;
    overflow-x: hidden;
    height: 100%;
  }

  body{
    margin: 0px;
    min-height: 100%;
    display: flex;
    flex-direction: column;
  }

</style>

<html>
<?php

//open the general info json file and take the json content for use in the pages
$jsonFileName = 'generalInfo/generalInfo.json';
$jsonFile = fopen($jsonFileName, "r") or die("unable to open file");
$generalInfo = json_decode(fread($jsonFile, filesize($jsonFileName)));
fclose($jsonFile);

//there shouldn't be an editor logged in while the escaperoom has started
if($generalInfo->started == "true" && array_key_exists('editing', $_SESSION)){
    unset($_SESSION['editing']);
    //refresh the page for the user
    header('Location: /');
}

//make sure the currentClass variable is set correctly
if(array_key_exists('class', $_SESSION)){
    $currentClass = $_SESSION['class'];
}
else {
    $currentClass = '3#';
}

$endTime = $connection->GetEndTime($currentClass);


//if the user isn't logged in display the home page
if(array_key_exists('admin', $_SESSION) && $_SESSION['admin'] === TRUE){
    include 'pages/admin/admin.php';
}
//display the end screen
else if($endTime){
    include 'pages/endScreen/endScreen.php';
}
//if the user has registered which class it is and the escaproom is spinning up display the:
//Ga zitten en rustig doen
else if($currentClass != '' && $generalInfo->spinnedUp === "true" && $generalInfo->started != "true"){
    include 'pages/spinningUp/spinningUp.php';
}
//if the user isn't logged in display the home page
else if(!array_key_exists('loggedIn', $_SESSION)){
    include 'pages/external/home.php';
}
//if the user is logged in display the internal content (page with different background and the user menu to navigate to the file system and profile)
else if(array_key_exists('username', $_SESSION)) {
    //open the users json file and take the json content for use in the pages
    $jsonFileName = 'users/' . $_SESSION['username'] . '/info/info.json';
    $jsonFile = fopen($jsonFileName, "r") or die("unable to open file");
    $user = json_decode(fread($jsonFile, filesize($jsonFileName)));
    fclose($jsonFile);

    //set the profilePicture location to the correct location (if it doesn't exist it will be set to the avatarNotFound)
    //the profile picture uses the imageHandler.php so the server doesn't need to expose the users profile for direct acces
    if (file_exists('users/' . $_SESSION['username'] . '/profilePicture.png')) {
        $profilePicture =  '/imageHandler.php?PROFILE=TRUE';
    } else {
        $profilePicture = 'media/avatarNotFound.png';
    }

    if($GLOBALS['URLExploded'][1] === "profile"){
        include 'pages/internal/profile.php';
    }
    else if($GLOBALS['URLExploded'][1] === "files"){
        include 'pages/internal/files.php';
    }
    else if($GLOBALS['URLExploded'][1] === "agenda"){
        include 'pages/internal/agenda.php';
    }
    else {
        include 'pages/internal/home.php';
    }
}

if($editing){
    include 'pages/editingOverlay.php';
}

?>

</html>