<?php

//
//This php file contains an API for all the tasks the website may need to perform:
//  --Logging in and out
//  --Creating, deleting and changing files
//  --Creating, deleting and chaning accounts
//  --Starting the escaproom
//All of these functions are accesed by the website like so:
//  /postEvents.php/FUNC=functionYouNeed
//These functions may also need extra variables to work, they are included like so
//  /postEvents.php/FUNC=functionYouNeed&EXTRAARG=argData&EXTRAARG2=arg2Data    etc.
//All of these functions check if these extra args are included and will not say anything if not: they will silently abort their operation
//Some of these functions also need the user to be logged into an editing account and will check if editing is enabled
//If editing isn't enabled the operation will also silently be aborted
//!There won't be anything coming back to the user from this file!
//

//function for easy directory deletion
//will recursivly empty the directorys and delete the directory(php can't delete filled directorys)
//is used by the DeleteFile function
function DeleteDir($path) {
    if (empty($path)) { 
        return false;
    }
    return is_file($path) ?
            @unlink($path) :
            array_map(__FUNCTION__, glob($path.'/*')) == @rmdir($path);
}



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

$jsonFileName = 'generalInfo/generalInfo.json';

//open the json file and retrieve it's content
$jsonFile = fopen($jsonFileName, "r") or die("unable to open file");
$generalInfo = json_decode(fread($jsonFile, filesize($jsonFileName)));
fclose($jsonFile);

//include the mysql handler
include 'mysql/handler.php';
// Create connection
$connection = new MySQLHandler();

//make sure the editing variable is set to true if the user is currently editing and the escaproom hasn't been started
if(array_key_exists('editing', $_SESSION) && $_SESSION['editing'] === TRUE && $generalInfo->started === "false"){
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

//---------------------------------------------------------------------------
//Login and log out functions
//---------------------------------------------------------------------------

//The loggin prompt will lead to this function
//This function will check the password and if the password for the user is correct it will set the session to login the user
//Some special cases are if in the json file editor or admin are set to true then it set the session to their cases
if($_GET['FUNC'] === "LogIn" && !empty($_POST)){
    if (array_key_exists('username', $_POST) && array_key_exists('password', $_POST)) {
        //check if the class is set if there are login attempts remaining
        if(array_key_exists('class', $_SESSION) && $connection->GetLoginAtemptsRemaining($_SESSION['class']) <= 0){
            //if no remaining check if there has been enough time past to give extra login attempts
            if(time() - $connection->GetLastLoginAtempt($_SESSION['class']) > $generalInfo->logginWaitTime){
                //add login attempts to a maximum of maxLogginAttempts, the amount of extra login attempts is the time past since previous attempt devided by the amount of time needed per extra atempt, rounded down
                $connection->SetLoginAttemptsRemaining($_SESSION['class'], min($generalInfo->maxLogginAtempts, round(((time() - $connection->GetLastLoginAtempt($_SESSION['class'])) / $generalInfo->logginWaitTime), PHP_ROUND_HALF_DOWN)));
            }
            else {
                //return feedback about why login has failed
                $_SESSION['logginFeedback'] = "Je moet nog " . number_format(round(($connection->GetLastLoginAtempt($_SESSION['class']) - time()  + $generalInfo->logginWaitTime) / 60, 0, PHP_ROUND_HALF_UP), 0) . " minuten wachten voordat je weer een wachtwoord in kan voeren";
                //refresh the page for the user
                header('Location: /');
                exit;
            }
        }
        if(array_key_exists('class', $_SESSION)) { $connection->SetLastLogginAtempt($_SESSION['class'], time()); }
        //check if the user exists
        $jsonFileName = 'users/' . strtolower($_POST['username']) . '/info/info.json';
        if(file_exists($jsonFileName)){
            //open the json file and get it's json content
            $jsonFile = fopen($jsonFileName, "r") or die("unable to open file");
            $user = json_decode(fread($jsonFile, filesize($jsonFileName)));
            fclose($jsonFile);
            
            //check if the password is correct
            if ($user->password == strtolower($_POST['password'])) {
                //regenerate the session id for safety (will prevent easy session id stealing)
                session_regenerate_id();
                //set the start time so the session can expire on loggin
                $_SESSION['startTime'] = time();
                //if the requested account is an admin account set admin to true
                if(isset($user->endGame) && $user->endGame === "true"){
                    if(array_key_exists('class', $_SESSION)){
                        $connection->SetEndTime($_SESSION['class'], (time() - ($generalInfo->startTime / 1000)) * 1000);
                    }
                    else{
                        $_SESSION['logginFeedback'] = "Niet ingelogd als klas";
                    }
                }
                //if the requested account is an admin account set admin to true
                else if(isset($user->admin) && $user->admin === "true"){
                    $_SESSION['admin'] = TRUE;
                }
                //if the requested account is an editor account set editing to true
                else if(isset($user->editor) && $user->editor === "true"){
                    $_SESSION['editing'] = TRUE;
                }
                //if the requested account is an loggin for a class set the class
                else if(isset($user->classLogin)){
                    $_SESSION['class'] = $user->classLogin;
                }
                //if the requested user is an normal account, the user should be logged in on that account
                else{
                    $_SESSION['loggedIn'] = TRUE;
                    $_SESSION['username'] = strtolower($_POST['username']);
                }
            }
            else{
                //use login attempt
                if(array_key_exists('class', $_SESSION)) { $connection->UseLoginAttempt($_SESSION['class']); }
                //set the feedback for the user correctly
                if(array_key_exists('class', $_SESSION) && $connection->GetLoginAtemptsRemaining($_SESSION['class']) <= 0){
                    $_SESSION['logginFeedback'] = "Wachtwoord of gebruikersnaam incorrect<br>Wacht " . number_format(round(($connection->GetLastLoginAtempt($_SESSION['class']) - time()  + $generalInfo->logginWaitTime) / 60, 0, PHP_ROUND_HALF_UP), 0) . " minuten voordat je weer een wachtwoord in kan voeren";
                }
                else{
                    $_SESSION['logginFeedback'] = "Wachtwoord of gebruikersnaam incorrect";                
                }
            }      
        }
        else {
            //use login attempt
            if(array_key_exists('class', $_SESSION)) { $connection->UseLoginAttempt($_SESSION['class']); }
            //set the feedback for the user correctly
            if(array_key_exists('class', $_SESSION) && $connection->GetLoginAtemptsRemaining($_SESSION['class']) <= 0){
                $_SESSION['logginFeedback'] = "Wachtwoord of gebruikersnaam incorrect<br>Wacht " . number_format(round(($connection->GetLastLoginAtempt($_SESSION['class']) - time()  + $generalInfo->logginWaitTime) / 60, 0, PHP_ROUND_HALF_UP), 0) . " minuten voordat je weer een wachtwoord in kan voeren";
            }
            else{
                $_SESSION['logginFeedback'] = "Wachtwoord of gebruikersnaam incorrect";                
            }
        }
        
        //register the attempt in the database
        if(array_key_exists('class', $_SESSION)){
            $connection->AddLoginAttempt($_SESSION['class'], $_POST['username'], $_POST['password']);
        }
    }
    //refresh the page for the user
    header('Location: /');
}
//Logout the current logged in account 
//will unset these parts of the session:
//  --loggedIn
//  --startTime
//  --username
else if ($_GET['FUNC'] === "LogOut") {
    unset($_SESSION['loggedIn']);
    unset($_SESSION['startTime']);
    unset($_SESSION['username']);
    //refresh the page for the user
    header('Location: /');
}
//Stop the editing
//will unset these parts of the session:
//  --editing
else if($_GET['FUNC'] === "StopEditing"){
    unset($_SESSION['editing']);
    //refresh the page for the user
    header('Location: /');
}
//Log out the admin
//will unset these parts of the session:
//  --admin
else if($_GET['FUNC'] === "StopAdmin"){
    unset($_SESSION['admin']);
    //no need to refresh, the caller will refresh on succes
}
//Force login
//will login the user without requiring the password (needs editing privilige)
else if($editing && $_GET['FUNC'] === "ForceLogin"){
    if(array_key_exists('username', $_POST)){
        $_SESSION['loggedIn'] = TRUE;
        $_SESSION['username'] = strtolower($_POST['username']);
        //refresh the page for the user
        header('Location: /');
    }
}



//---------------------------------------------------------------------------
//Account creation and changing
//---------------------------------------------------------------------------

//Create an account wich:
//  --adds a folder in the users folder with the new users name
//  --adds the info folder in the users folder
//  --adds the files folder in the users folder
//  --adds the userCreation/defaultInfo.json to the users /info/info.json
//  --opens the info/info.json and updates the username and password
else if($editing && $_GET['FUNC'] === "CreateAccount" && !empty($_POST)){
    //first check if the passwords equal
    if(array_key_exists('password1', $_POST) && array_key_exists('password2', $_POST) && array_key_exists('username', $_POST)) {
        $username = strtolower($_POST['username']);
        if(strtolower($_POST['password1']) === strtolower($_POST['password2']) && !file_exists("users/" . $username)){
            //create the directorys
            mkdir("users/" . $username);
            mkdir("users/" . $username . "/info");
            mkdir("users/" . $username . "/files");
    
            //copy the defaultInfo.json into the info folder as info.json
            copy("userCreation/defaultInfo.json", "users/" . $username . "/info/info.json");
    
            $jsonFileName = "users/" . $username . "/info/info.json";
    
            //open the info/info.json
            $jsonFile = fopen($jsonFileName, "r") or die("unable to open file");
            $user = json_decode(fread($jsonFile, filesize($jsonFileName)));
            fclose($jsonFile);
            
            //update the username and password
            $user->username = $username;
            $user->password = strtolower($_POST['password1']);
    
            //close the json file
            $jsonFile = fopen($jsonFileName, "w");
            fwrite($jsonFile, json_encode($user));
            fclose($jsonFile);
    
        }
    }
    //refresh the page for the user
    header('Location: /');
}
//Change the account info of the current logged in account:
//
//Will open the info/info.json and edit the:
//name, mail, phonenumber, birthday, function, color, hobbys
else if($editing && $_GET['FUNC'] === "ProfileChanges"){
    //retrieve the input
    $profileChanges = json_decode(file_get_contents('php://input'));
    $jsonFileName = 'users/' . strtolower($profileChanges->username) . '/info/info.json';
    if(file_exists($jsonFileName)){

        //open the info/info.json and retrieve it's content
        $jsonFile = fopen($jsonFileName, "r") or die("unable to open file");
        $user = json_decode(fread($jsonFile, filesize($jsonFileName)));
        fclose($jsonFile);

        //update the values
        $user->name = $profileChanges->name;
        $user->mail = $profileChanges->mail;
        $user->phonenumber = $profileChanges->phonenumber;
        $user->birthday = $profileChanges->birthday;
        $user->function = $profileChanges->function;
        $user->color = $profileChanges->color;
        $user->hobbys = nl2br($profileChanges->hobbys);
        
        //open the json file and write the modified content
        $jsonFile = fopen($jsonFileName, "w");
        fwrite($jsonFile, json_encode($user));
        fclose($jsonFile);

        //refresh the page for the user
        header('Location: /');
    }
}
//Change the username and/or password of an account
//will update if one of them(password or username) is in the request to be updated
else if($editing && $_GET['FUNC'] === "ChangeAccount" && !empty($_POST)){
    if(array_key_exists('username', $_POST) && (array_key_exists('newUsername', $_POST) || (array_key_exists('newPassword1', $_POST) && array_key_exists('newPassword2', $_POST)))){

        $jsonFileName = 'users/' . strtolower($_POST['username']) . '/info/info.json';

        //open the json file and retrieve it's content
        $jsonFile = fopen($jsonFileName, "r") or die("unable to open file");
        $user = json_decode(fread($jsonFile, filesize($jsonFileName)));
        fclose($jsonFile);

        //if POST contains an password and the password are equal update the password in the json file
        if($_POST['newPassword1'] !== '' && strtolower($_POST['newPassword1']) === strtolower($_POST['newPassword2'])){            
            $user->password = strtolower($_POST['newPassword1']);
        }
        //if POST contains an username update the username in the json file
        if($_POST['newUsername'] !== ''){
            $user->username = strtolower($_POST['newUsername']);
        }
        //open the json file and write the modified content
        $jsonFile = fopen($jsonFileName, "w");
        fwrite($jsonFile, json_encode($user));
        fclose($jsonFile);

        //if POST contains an username rename the users folder to the new username
        if($_POST['newUsername'] !== ''){
            rename("users/" . strtolower($_POST['username']), "users/" . strtolower($_POST['newUsername']));
        }

        //refresh the page for the user
        header('Location: /');
    }
}
//update the profile picture of the current logged in account
else if($editing && $_GET['FUNC'] === "ProfilePictureUpload"){

    //if the file type isn't an image throw an error
    if (!(in_array($_FILES['file']['type'], ['image/png', 'image/jpg', 'image/jpeg']))) {
        die;
    }

    $filename = 'users/' . $_SESSION['username'] . '/profilePicture.png';
    
    //move the temporary received file(in the post request) to the users profilePicture.png
    move_uploaded_file($_FILES['file']['tmp_name'], $filename);
}




//---------------------------------------------------------------------------
//File creation and changing
//---------------------------------------------------------------------------

//Create an file for the current logged in user
//
//A file can only be of certain types to prevent the posibility of injecting .php files into the system and it running malicious code
//Will also if the filename is already in use add a number to make sure the filenames don't overlap
else if($editing && $_GET['FUNC'] === "CreateFile"){
    if(array_key_exists('TYPE', $_GET) && array_key_exists('FOLDER', $_GET)){
        
        //set the correct filename
        if($_GET['TYPE'] == 'text'){
            $fileName = "newTextFile.txt";
        }
        else if($_GET['TYPE'] == 'image'){
            $fileName = "newTextFile.png";
        }
        else if($_GET['TYPE'] == 'folder'){
            $fileName = "newFolder";
        }

        //check if the file already exists for the current logged in user
        //if it does add a number in front of the name
        if (file_exists('users/' . $_SESSION['username'] . '/files/' . $_GET['FOLDER'] . '/' . $fileName)) {
            $i = 1;
            while(file_exists('users/' . $_SESSION['username'] . '/files/' . $_GET['FOLDER'] . '/' . $i."_".$fileName)) {
                $i++;
            }
            $fileName = $i."_".$fileName;
        }

        $fileName = 'users/' . $_SESSION['username'] . '/files/' . $_GET['FOLDER'] . '/' . $fileName;

        //copy the default files to the new file location
        if($_GET['TYPE'] == 'text'){
            copy('userCreation/newTextFile.txt', $fileName);
        }
        else if($_GET['TYPE'] == 'image'){
            copy('userCreation/newImage.png', $fileName);
        }
        else if($_GET['TYPE'] == 'folder'){
            mkdir($fileName);
        }
        //no need to refresh, the caller will refresh on succes
    }
}
//delete a file/folder for the current logged in user
else if($editing && $_GET['FUNC'] === "DeleteFile"){
    if(array_key_exists('FILE', $_GET)){
        //cals DeleteDir which is a function who will recursivly delete directorys
        //(you can only delete empty directorys in php)
        DeleteDir("users/" . $_SESSION['username'] . '/files' . $_GET['FILE']);
    }
    //no need to refresh, the caller will refresh on succes
}
//change a file/folder name
else if($editing && $_GET['FUNC'] === "ChangeFileName"){
    //check if th original file exists
    if(array_key_exists('FILE', $_GET) && array_key_exists('NEWFILENAME', $_GET) && file_exists("users/" . $_SESSION['username'] . '/files' . $_GET['FILE'])){
        //get the directory where the file to be changed is located
        $directory = dirname($_GET['FILE']);
        //get the extension of the file if it isn't a directory
        if(pathinfo($_GET['FILE'], PATHINFO_EXTENSION)){
            $extension = '.' . pathinfo($_GET['FILE'], PATHINFO_EXTENSION);
        }
        else{
            $extension = '';
        }
        //rename the file (NEWFILENAME doesn't contain the directory it is contained in, the directory is taken from the FILE input)
        rename("users/" . $_SESSION['username'] . '/files' . $_GET['FILE'], "users/" . $_SESSION['username'] . '/files' . $directory . '/' . str_replace('.', '', $_GET['NEWFILENAME']) . $extension);
    }
    //no need to refresh, the caller will refresh on succes
}
//Edit the contents of a textfile
else if($editing && $_GET['FUNC'] === "TextFileEdit"){
    //check if the file exists
    if(array_key_exists('FILE', $_GET) && file_exists("users/" . $_SESSION['username'] . '/files' . $_GET['FILE'])){
        //open the text file
        $textFile = fopen("users/" . $_SESSION['username'] . '/files' . $_GET['FILE'], "w");

        //retrieve the new content of the text file from the POST request and make sure there will be at least one character in the file
        $newContent = file_get_contents('php://input');
        if($newContent === ''){
            $newContent = '-';
        }
        //write the new content to the textfile and close it
        fwrite($textFile, $newContent);
        fclose($textFile);
    }
    //no need to refresh, the caller will refresh on succes
}
//Edit the contents of a image file
else if($editing && $_GET['FUNC'] === "ImageFileEdit"){
    //check if the file exists
    if(array_key_exists('FILE', $_GET) && file_exists('users/' . $_SESSION['username'] . '/files' . $_GET['FILE'])){
        //make sure the received file is an image file
        if (!(in_array($_FILES['file']['type'], ['image/png', 'image/jpg', 'image/jpeg']))) {
            die;
        }

        //move the received file to the edited file location
        $filename = 'users/' . $_SESSION['username'] . '/files' . $_GET['FILE'];
        move_uploaded_file($_FILES['file']['tmp_name'], $filename);
    }
    //no need to refresh, the caller will refresh on succes
}

//---------------------------------------------------------------------------
//Escaproom starting and stopping
//---------------------------------------------------------------------------

//!will start the escaperoom!
//
else if($admin && $_GET['FUNC'] === "StartEscaperoom"){
    $jsonFileName = 'generalInfo/generalInfo.json';

    //open the json file and retrieve it's content
    $jsonFile = fopen($jsonFileName, "r") or die("unable to open file");
    $info = json_decode(fread($jsonFile, filesize($jsonFileName)));
    fclose($jsonFile);

    //set started to true and set the startTime
    $info->started = "true";
    $info->startTime = time() * 1000;

    //open the json file and write the modified content
    $jsonFile = fopen($jsonFileName, "w");
    fwrite($jsonFile, json_encode($info));
    fclose($jsonFile);
}
//!will spin up the escaproom:
//  --If session for class is set website will display: "we gaan zo beginnnen"
//  --Editing is forbidden
else if($admin && $_GET['FUNC'] === "SpinUpEscaperoom"){
    //set spinnedUp to true
    $generalInfo->spinnedUp = "true";

    //open the json file and write the modified content
    $jsonFile = fopen($jsonFileName, "w");
    fwrite($jsonFile, json_encode($generalInfo));
    fclose($jsonFile);
}
//Makes you able to check if the escaproom has started yet:
//Used by the starting screen to check if it needs to update
else if($_GET['FUNC'] === "HasEscaperoomStarted"){
    echo $generalInfo->started;
}


?>