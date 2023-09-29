<?php $addSaveButton = TRUE; ?>

<style>
    body {
        background-image: url("/media/parallax-4.png");
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    main {
        width: 100%;
        height: 100%;
    }

    .profile table {
        table-layout: fixed;
        width: 550px;
    }

    .profile {
        background-color: white;
        text-align: center;
        color: black;
        margin: 20px;
        display: grid;
        grid-template-columns: auto;
        grid-template-rows: auto;
        justify-content: space-evenly;
        width: 600px;
        margin: 200px auto;
    }

    .profile h1 {
        text-align: center;
        color: black;
        font-size: 24px;
        padding: 20px 0 20px 0;
        border-bottom: 1px solid #dee0e4;
    }

    .profile .image {
        border-bottom: 1px solid #dee0e4;
    }

    .profile img {
        border-radius: 50%;
        height: 100px;
        width: 100px;
        object-fit: cover;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: 5px;
        margin-top: 5px;
    }
</style>

<?php include 'pages/internal/header.php'; ?>

<main>
    <div class="profile">
        <div class="image" <?php if($editing) {echo 'ondrop="UploadProfileImageFile(event)" ondragover="return false"';}?>>
            <img src="<?php echo $profilePicture; ?>"></img>
            <?php if($editing) { echo '<p><input type="file" id="selectfile" /></p>'; } ?>
        </div>
        <table>
            <tr>
                <td style="text-align: right;"><b>Username</b></td>
                <td style="text-align: center;" width=40px><a>-</a></td>
                <td style="text-align: left;"><?php echo $user->username; ?></a></td>
            </tr>
            <tr>
                <td style="text-align: right;"><b>Naam</b></td>
                <td style="text-align: center;" width=40px><a>-</a></td>
                <td style="text-align: left;"><a id="infoName" <?php if($editing) { echo 'contenteditable="true" class="noEnters"'; }?>><?php echo $user->name; ?></a></td>
            </tr>
            <tr>
                <td style="text-align: right;"><b>Mail</b></td>
                <td style="text-align: center;" width=40px><a>-</a></td>
                <td style="text-align: left;"><a id="infoMail" <?php if($editing) { echo 'contenteditable="true" class="noEnters"'; }?>><?php echo $user->mail; ?></a></td>
            </tr>
            <tr>
                <td style="text-align: right;"><b>Telefoonnummer</b></td>
                <td style="text-align: center;" width=40px><a>-</a></td>
                <td style="text-align: left;"><a id="infoPhonenumber" <?php if($editing) { echo 'contenteditable="true" class="noEnters"'; }?>><?php echo $user->phonenumber; ?></a></td>
            </tr>
            <tr>
                <td style="text-align: right;"><b>Geboortedatum</b></td>
                <td style="text-align: center;" width=40px><a>-</a></td>
                <td style="text-align: left;"><a id="infoBirthday" <?php if($editing) { echo 'contenteditable="true" class="noEnters"'; }?>><?php echo $user->birthday; ?></a></td>
            </tr>
            <tr>
                <td style="text-align: right;"><b>Functie</b></td>
                <td style="text-align: center;" width=40px><a>-</a></td>
                <td style="text-align: left;"><a id="infoFunction" <?php if($editing) { echo 'contenteditable="true" class="noEnters"'; }?>><?php echo $user->function; ?></a></td>
            </tr>
            <tr>
                <td style="text-align: right;"><b>Lievelingskleur</b></td>
                <td style="text-align: center;" width=40px><a>-</a></td>
                <td style="text-align: left;"><a id="infoColor" <?php if($editing) { echo 'contenteditable="true" class="noEnters"'; }?>><?php echo $user->color; ?></a></td>
            </tr>
            <tr>
                <td style="text-align: center;" colspan=3><b>Hobbys:</b></td>
            </tr>
            <tr>
                <td style="text-align: center;" colspan=3><a id="infoHobbys" <?php if($editing) { echo 'contenteditable="true"'; }?>><?php echo $user->hobbys; ?></a></td>
            </tr>
        </table>
    </div>
</main>

<?php if($editing) {
    echo '<script>

    var noEnters = document.getElementsByClassName(\'noEnters\')
    
    for(const element of noEnters){
        element.addEventListener(\'keydown\', (e) => {
            if (e.key === \'Enter\'){
                e.preventDefault(); 
            }
    
        });
    }
    
    function SaveChangesExternal(){
        let xhr = new XMLHttpRequest();
        xhr.open(\'POST\', \'/postEvents.php?FUNC=ProfileChanges\');
        xhr.send(\'{ "name": "\' + document.getElementById("infoName").textContent + \'",\'
                    + \'"username": "\' + \'' . $_SESSION['username'] . '\' + \'",\'
                    + \'"mail": "\' + document.getElementById("infoMail").textContent + \'",\'
                    + \'"phonenumber": "\' + document.getElementById("infoPhonenumber").textContent + \'",\'
                    + \'"birthday": "\' + document.getElementById("infoBirthday").textContent + \'",\'
                    + \'"function": "\' + document.getElementById("infoFunction").textContent + \'",\'
                    + \'"color": "\' + document.getElementById("infoColor").textContent + \'",\'
                    + \'"hobbys": "\' + document.getElementById("infoHobbys").innerHTML.replace(/(?:\r\n|\r|\n)/g, \'<br>\') + \'"}\');
    }
    
    document.getElementById(\'selectfile\').onchange = function() {
        ProfileImageUpload(document.getElementById(\'selectfile\').files[0]);
    };
    
    function ProfileImageUpload(fileOBJ) {
        if(fileOBJ != undefined) {
            var formData = new FormData();                  
            formData.append(\'file\', fileOBJ);
            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "postEvents.php?FUNC=ProfilePictureUpload&USER=' .$_SESSION['username'] . '", true);
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

    </script>';
}?>

<?php include 'pages/footer.php'; ?>