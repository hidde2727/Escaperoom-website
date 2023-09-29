<style>
    .editingOverlay {
        position: fixed;
        bottom: 0px;
        right: 0px;
        z-index: 1;
        user-select: none;
    }

    .editingOverlay form {
        display: inline;
        padding-top: 20px;
    }

    .editingOverlay form input[type="submit"],
    .editingOverlay a {
        padding: 15px;
        background-color: #ed4700;
        border: 2px;
        border-color: #dee0e4;
        border-style: solid;
        border-width: 2px;
        cursor: pointer;
        font-weight: bold;
        color: #ffffff;
        transition: background-color 0.2s;
        font-size: 1em;
        display: inline;
        text-decoration: none;
    }

    .editingOverlay form input[type="submit"]:hover,
    .editingOverlay a:hover {
        background-color: #ba3271;
        transition: background-color 0.2s;
    }

    .expandable form input[type="submit"],
    .expandable a {
        padding: 15px;
        background-color: #ed4700;
        border: 2px;
        border-color: #dee0e4;
        border-style: solid;
        border-width: 2px;
        cursor: pointer;
        font-weight: bold;
        color: #ffffff;
        transition: background-color 0.2s;
        font-size: 1em;
        display: inline;
        text-decoration: none;
    }

    .expandable form input[type="submit"]:hover,
    .expandable a:hover {
        background-color: #ba3271;
        transition: background-color 0.2s;
    }

    .expandable form input[type="password"],
    .expandable form input[type="text"],
    .expandable form select {
        width: 310px;
        height: 50px;
        border: 1px solid #dee0e4;
        margin-bottom: 20px;
        padding: 0 15px;
    }

    .expandable form label {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 50px;
        height: 50px;
        background-color: #ed4700;
        color: #ffffff;
    }

    .expandable {

        cursor: context-menu;

        background-color: white;

        display: block;
        overflow: hidden;
        position: absolute;
        left: -200px;
        bottom: 52px;

        max-height: 0px;
        transition: max-height 0.2s ease-out;

        z-index: 1;

    }

    .expandable form{
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        padding-top: 20px;
        
        width: 400px;
    }

    .expandable form input[type="submit"] {
        width: 100%;
        padding: 15px;
        margin-top: 20px;
        background-color: #ed4700;
        border: 0;
        cursor: pointer;
        font-weight: bold;
        color: #ffffff;
        transition: background-color 0.2s;
    }

</style>

<script>

    function CollapseEditingOverlay(item, expandableName) {
        var child = item.parentNode.getElementsByClassName(expandableName)[0];
        if (child.style.maxHeight) {
            child.style.maxHeight = null;
            child.style.borderTop = '0px';
        } else {
            //first colapse others:
            var expandables = item.parentNode.getElementsByClassName("expandable");
            for (const element of expandables) {
                element.style.maxHeight = null;
                element.style.borderTop = '0px';
            }
            child.style.maxHeight = child.scrollHeight + "px";
            child.style.borderTop = '1px solid #dee0e4';
        }
    }

</script>

<div class="editingOverlay">
    <?php if($addSaveButton) { echo '<a onclick="SaveChangesExternal();">Save changes</a>';} ?>
    <a onclick='CollapseEditingOverlay(this, "accountForceLogin")'>Force login</a>
    <a onclick='CollapseEditingOverlay(this, "accountChanging")'>Edit account</a>
    <a onclick='CollapseEditingOverlay(this, "accountCreation")'>
        Creëer account
    </a>
    <form class="stopEditing" action="/postEvents.php?FUNC=StopEditing" method="post">
        <input type="submit" value="Stop editing">
    </form>


    <div style="position: relative">
        <div class="accountChanging expandable">
            <div class="icon" style=" position: relative; margin-left: auto; margin-right: 0; display: block;">
                <form action="/postEvents.php?FUNC=ChangeAccount" method="post">
                    <label for="username">
                        <i class="fa fa-user"></i>
                    </label>
                    <select name="username" placeholder="Gebruiker" id="oldUsername" required>
                        <?php
                            foreach(glob('users/*') as $userFolder) {
                                $username = pathinfo($userFolder, PATHINFO_FILENAME);
                                echo '<option value="' . $username . '">' . $username . '</option>';
                            }
                        ?>
                    </select>
                    <label for="newUsername">
                        <i class="fa fa-user"></i>
                    </label>
                    <input type="text" name="newUsername" placeholder="Nieuwe gebruikersnaam" id="newUsername">
                    <label for="newPassword1">
                        <i class="fa fa-lock"></i>
                    </label>
                    <input type="text" name="newPassword1" placeholder="Nieuw wachtwoord" id="newPassword">
                    <label for="newPassword2">
                        <i class="fa fa-lock"></i>
                    </label>
                    <input type="text" name="newPassword2" placeholder="Herhaal nieuw wachtwoord" id="newPasswordRepeat">
                    <input type="submit" value="Creëer account">
                </form>
            </div>
        </div>
        <div class="accountCreation expandable">
            <div class="icon" style=" position: relative; margin-left: auto; margin-right: 0; display: block;">
                <form action="/postEvents.php?FUNC=CreateAccount" method="post">
                    <label for="username">
                        <i class="fa fa-user"></i>
                    </label>
                    <input type="text" name="username" placeholder="Gebruikersnaam" id="usernameCreate" required>
                    <label for="password1">
                        <i class="fa fa-lock"></i>
                    </label>
                    <input type="text" name="password1" placeholder="Wachtwoord" id="passwordCreate" required>
                    <label for="password2">
                        <i class="fa fa-lock"></i>
                    </label>
                    <input type="text" name="password2" placeholder="Herhaal Wachtwoord" id="passwordCreateRepeat" required>
                    <input type="submit" value="Creëer account">
                </form>
            </div>
        </div>
    </div>
    <div class="accountForceLogin expandable">
            <div class="icon" style=" position: relative; margin-left: auto; margin-right: 0; display: block;">
                <form action="/postEvents.php?FUNC=ForceLogin" method="post">
                    <label for="username">
                        <i class="fa fa-user"></i>
                    </label>
                    <select name="username" placeholder="Gebruiker" id="username" required>
                        <?php
                            foreach(glob('users/*') as $userFolder) {
                                $username = pathinfo($userFolder, PATHINFO_FILENAME);
                                echo '<option value="' . $username . '">' . $username . '</option>';
                            }
                        ?>
                    </select>
                    <input type="submit" value="Login">
                </form>
            </div>
        </div>
</div>