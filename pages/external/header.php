<style>
    .collumn h1,
    .collumn h2,
    .collumn h3,
    .collumn h4,
    .collumn h5,
    .collumn h6 {
        white-space: nowrap;
    }

    header {
        position: sticky;
        top: 0px;
        background-color: white;
        z-index: 1;
        margin: 0px;
        padding: 0px;
        user-select: none;
    }


    header nav {
        padding-left: 0;
        display: grid;
        grid-template-rows: 1fr;
        grid-template-columns: repeat(7, auto);
    }

    .timer {
        margin-top: auto;
        margin-bottom: auto;
        margin-left: auto;
        font-size: 3em;
    }

    .timer:hover {
        color: #ed4700;
        cursor: auto;
    }

    header nav a,
    header nav h2 {
        text-align: center;
        color: #ed4700;
        text-decoration: none;
        margin-top: auto;
        margin-bottom: auto;
    }

    header nav a:hover {
        color: #ba3271;
        cursor: pointer;
    }

    .login {

        cursor: context-menu;

        width: 400px;
        background-color: white;

        display: block;
        overflow: hidden;
        position: absolute;
        right: 0px;

        max-height: 0;
        transition: max-height 0.2s ease-out;
    }

    .login h1 {
        text-align: center;
        color: black;
        font-size: 24px;
        padding: 20px 0 20px 0;
        border-bottom: 1px solid #dee0e4;
    }

    .login form {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        padding-top: 20px;
    }

    .login form label {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 50px;
        height: 50px;
        background-color: #ed4700;
        color: #ffffff;
    }

    .login form input[type="password"],
    .login form input[type="text"] {
        width: 310px;
        height: 50px;
        border: 1px solid #dee0e4;
        margin-bottom: 20px;
        padding: 0 15px;
    }

    .login form input[type="submit"] {
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

    .login form input[type="submit"]:hover {
        background-color: #ba3271;
        transition: background-color 0.2s;
    }
</style>

<header>
    <nav class="collumn" style="min-width: 800px; max-width: 1200px; margin: auto; display: flex;">
        <img src="media/ShmelLogo.jpg" alt="Shmel"
            style="height: 50px; margin-top: auto; margin-bottom: auto; display: block;">

        <a class="timer"> 00:00:00 </a>

        <div class="icon" style=" position: relative; margin-left: auto; margin-right: 0; display: block;">
            <i style="line-height:80px;" class="fa fa-user-circle fa-3x" onclick="CollapseLoggin(this);"></i>
            <div class="login">
                <form action="/postEvents.php?FUNC=LogIn" method="post">
                    <label for="password">
                        <i class="fa fa-user"></i>
                    </label>
                    <input type="text" name="username" placeholder="Gebruikersnaam" id="username" required>
                    <label for="password">
                        <i class="fa fa-lock"></i>
                    </label>
                    <input type="text" name="password" placeholder="Wachtwoord" id="password" required>

                    <a><?php if(array_key_exists('logginFeedback', $_SESSION)) { echo $_SESSION['logginFeedback']; } ?></a>

                    <input type="submit" value="Login">
                </form>
            </div>
        </div>
    </nav>
</header>

<script>

    function CollapseLoggin(item) {
        var child = item.parentNode.getElementsByClassName('login')[0];
        if (child.style.maxHeight) {
            child.style.maxHeight = null;
            child.style.borderTop = '0px';
        } else {
            child.style.maxHeight = child.scrollHeight + "px";
            child.style.borderTop = '1px solid #dee0e4';
        }
    }

    function UpdateTimer() {
        var start = new Date(<?php echo $generalInfo->startTime + $connection->GetExtraTime($currentClass); ?>);
        var difference = new Date(Date.now() - start);
        var time = difference.toISOString().slice(11, 19);
        var item = document.getElementsByClassName("timer");
        item[0].textContent = "<?php echo $currentClass . ' - ' ?>" + time;
    }

    window.setInterval(UpdateTimer, 1000);

<?php 
if(array_key_exists('logginFeedback', $_SESSION)){
    echo "
    var child = document.getElementsByClassName('login')[0];
    child.style.maxHeight = child.scrollHeight + 'px';
    ";
    unset($_SESSION['logginFeedback']);
}
?>

</script>