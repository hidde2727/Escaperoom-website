<?php include 'pages/internal/header.php'; ?>

<style>

    body {
        background-image: url("/media/parallax-4.png");
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    main {
        height: 100%;
        margin-top: auto;
        margin-bottom: auto;
    }

    .FileArea {
        margin: 30px;
        border: 4px solid #dee0e4;
        padding: 20px;
        height: 100%;
    }

    .FileArea .FileBackground {
        background-color: white;
        padding: 10px;
    }

    .FileArea img {
        max-height: 90vh;
        max-width: 90vw;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    .FileArea .File i {
        line-height:80px;
        color: black;
        margin-right: 10px;
    }

    .FileArea a {
        text-decoration: none;
        color: black;
    }

    .FileArea .File:hover {
        color: #ed4700;
        transition: color 0.2s;
    }

    .FileArea .FileNavBar {
        display: flex;
        align-items: center;
        background-color: white;
        margin-bottom: 10px;
        padding-left: 10px;
        padding-right: 10px;
    }

    .FileArea .FileNavBar i {
        line-height: 40px;
        margin-right: 20px;
    }

    <?php if($editing) { echo '
    .FileArea .FileNavBar .toLeft {
        margin-left: auto;
    }

    .FileArea .FileContainer {
        display: flex;
        width: 100%;
        align-items: center;
    }
    ';} ?>

</style>

<main>

<div class="FileArea">

<div class="FileNavBar">

<?php

$urlParts = parse_url($_SERVER['REQUEST_URI']);
$folderLocation = str_replace('%20', ' ', substr($urlParts['path'], 6));;
$previousFolder = dirname($folderLocation);

echo '<a href="/files' . $previousFolder . '"><i class="fa fa-reply fa-2x"></i></a>';

echo '<a href="/files">home</a>';

$fileLocation = glob ('users/' . $_SESSION['username'] . '/files'. $folderLocation . '.*');

$isFile = $folderLocation !== '' && $folderLocation !== '/' && count($fileLocation) > 0;

if($folderLocation !== '') {
    $navBarLocation = "";
    $urlPartsExploded = explode('/', substr($folderLocation, 1)) ;
    foreach($urlPartsExploded as $file) {
        if($file !== ''){
            $navBarLocation = $navBarLocation . '/' . $file;
            echo '<a> > </a>';
            if(end($urlPartsExploded) !== $file || count($fileLocation) <= 0){
                echo '<a href="/files' . $navBarLocation . '">' . $file . '</a>';
            }
            else {
                echo '<a href="/files' . $navBarLocation . '">' . $file . '.' . pathinfo($fileLocation[0], PATHINFO_EXTENSION) . '</a>';
            }
        }
    }
}

if($editing && !$isFile){
    echo '<span class="toLeft">';
    echo '<i class="fa fa-file-text-o fa-2x" onclick="AddFile(\''. $folderLocation . '\',\'text\')"></i>';
    echo '<i class="fa fa-file-image-o fa-2x" onclick="AddFile(\''. $folderLocation . '\',\'image\')"></i>';
    echo '<i class="fa fa-folder-o fa-2x" onclick="AddFile(\''. $folderLocation .'\',\'folder\')"></i>';
    echo '</span>';
}

?>

</div>

<div class="FileBackground">
<?php

if($isFile){
    $fileLocation = $fileLocation[0];
    $fileExtension = pathinfo($fileLocation, PATHINFO_EXTENSION);
    if($fileExtension === 'png'){
        //display an image
        $file = substr($fileLocation, strpos($fileLocation, '/', 1));
        $file = substr($file, strpos($file, '/', 1));
        $file = substr($file, strpos($file, '/', 1));
        $file = substr($file, 0, strpos($file, '.', 1));
        echo '<div class="ImageFile" ';
        if($editing) {echo 'ondrop="UploadProfileImageFile(event)" ondragover="return false"';}
        echo '>';
        echo '<img src="/imageHandler.php?IMG=' . $file . '"></img>';
        if($editing) { echo '<p><input type="file" id="selectfile" /></p>'; }
        echo '</div>';
    }
    else{
        $addSaveButton = TRUE;
        echo '<a contenteditable="true" class="TextFileEdit">';
        $file = fopen($fileLocation, "r") or die("Unable to open file!");
        echo fread($file,filesize($fileLocation));
        fclose($file);
        echo '</a>';
    }
}
else{
    //it is a folder

    foreach(glob('users/' . $_SESSION['username'] . '/files'. $folderLocation . '/*') as $file) {
        $fileName = pathinfo($file, PATHINFO_FILENAME);
        if(is_file($file)){
            echo '<div class="FileContainer"><a class="File" href="/files'. $folderLocation . '/' . $fileName . '">';
            $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
            if($fileExtension == "txt"){
                echo '<i class="fa fa-file-text-o fa-3x"></i>';
            }
            else if($fileExtension == "png" || $fileExtension == "jpg" || $fileExtension == "jpeg"){
                echo '<i class="fa fa-file-image-o fa-3x"></i>';
            }
            else{
                echo '<i class="fa fa-file-o fa-3x"></i>';
            }
            echo $fileName . '.' . $fileExtension;
            echo '</a>';
            if($editing){
                echo '<span class="fileNameEdit" style="visibility:hidden;margin-left: auto; margin-right: 10px;"><span class="fileName" contenteditable="true">' . $fileName . '</span><span>' . '.' . $fileExtension . '</span></span>';
                echo '<i style="margin-right: 10px;" class="fa fa-pencil-square-o fa-2x" onclick="StartFileNameEdit(\''. $folderLocation . '/' . $fileName . '.' . $fileExtension . '\', this);"></i>';
                echo '<i style="margin-right: 10px;" class="fa fa-trash-o fa-2x" onclick="DestroyFile(\''. $folderLocation . '/' . $fileName . '.' . $fileExtension . '\')"></i>';
            }
            echo '</div>';
        }
        else {
            echo '<div class="FileContainer"><a class="File" href="/files'. $folderLocation . '/' . $fileName . '">';
            echo '<i class="fa fa-folder fa-3x"></i>';
            echo $fileName;
            echo '</a>';
            if($editing){
                echo '<span class="fileNameEdit" style="visibility:hidden;margin-left: auto; margin-right: 10px;"><span class="fileName" contenteditable="true">' . $fileName . '</span></span>';
                echo '<i style="margin-right: 10px;" class="fa fa-pencil-square-o fa-2x" onclick="StartFileNameEdit(\''. $folderLocation . '/' . $fileName . '\', this);"></i>';
                echo '<i style="margin-right: 10px;" class="fa fa-trash-o fa-2x" onclick="DestroyFile(\''. $folderLocation . '/' . $fileName . '\')"></i>';
            }
            echo '</div>';
        }
    }
}
?>
</div>
</div>

<script>

<?php if($editing){
    echo 'function AddFile(location, type) {
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", "/postEvents.php?FUNC=CreateFile&FOLDER="+ location + "&TYPE=" + type, true);
        xhttp.onload = function(event) {
            if (xhttp.status == 200 && this.responseText != "error") {
                window.location.reload();
            }
        }
        xhttp.send(null);
    }
    
    function StartFileNameEdit(originalFileName, initiator) {
        var element = initiator.parentNode.getElementsByClassName("fileNameEdit")[0];
        if(element.style.visibility == "hidden"){
            //first time call
            element.style.visibility = "visible";
            element.parentNode.getElementsByClassName("fa-pencil-square-o")[0].classList.add("fa-floppy-o");
            element.parentNode.getElementsByClassName("fa-pencil-square-o")[0].classList.remove("fa-pencil-square-o");
        }
        else if(element.style.visibility == "visible"){
            //second time call
            element = element.getElementsByClassName("fileName")[0];
            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "/postEvents.php?FUNC=ChangeFileName&FILE=" + originalFileName + "&NEWFILENAME=" + element.textContent, true);
            xhttp.onload = function(event) {
                if (xhttp.status == 200 && this.responseText != "error") {
                    window.location.reload();
                }
            }
            xhttp.send(null);
        }
    }
    
    function DestroyFile(item) {
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", "/postEvents.php?FUNC=DeleteFile&FILE=" + item, true);
        xhttp.onload = function(event) {
            if (xhttp.status == 200 && this.responseText != "error") {
                window.location.reload();
            }
        }
        xhttp.send(null);
    }
    
    var noEnters = document.getElementsByClassName("fileName")
        
    for(const element of noEnters){
        element.addEventListener("keydown", (e) => {
            if (e.key === "Enter"){
                e.preventDefault(); 
            }
        });
    }';
}?>

<?php
    if($editing && $isFile){

        $file = substr($fileLocation, strpos($fileLocation, '/', 1));
        $file = substr($file, strpos($file, '/', 1));
        $file = substr($file, strpos($file, '/', 1));

        echo '
        function SaveChangesExternal(){

            let xhr = new XMLHttpRequest();
            xhr.open(\'POST\', "/postEvents.php?FUNC=TextFileEdit&FILE='. $file . '");
            xhr.onload = function(event) {
                if (xhr.status == 200 && this.responseText != "error") {
                    window.location.reload();
                }
            }
            xhr.send(document.getElementsByClassName("TextFileEdit")[0].innerHTML);
        }
        
        document.getElementById(\'selectfile\').onchange = function() {
            ProfileImageUpload(document.getElementById(\'selectfile\').files[0]);
        };
        
        function ProfileImageUpload(fileOBJ) {
            if(fileOBJ != undefined) {
                var formData = new FormData();                  
                formData.append(\'file\', fileOBJ);
                var xhttp = new XMLHttpRequest();
                xhttp.open("POST", "/postEvents.php?FUNC=ImageFileEdit&FILE=' . $file . '", true);
                xhttp.onload = function(event) {
                    if (xhttp.status == 200 && this.responseText != "error") {
                        window.location.reload();
                    } else {
                        console.log("Error occurred when trying to upload your file.");
                    }
                }
             
                xhttp.send(formData);
            }
        }
        ';
    }

?>

</script>

</main>

<?php include 'pages/footer.php';