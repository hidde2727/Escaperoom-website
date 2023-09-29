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
    }


    header nav {
        padding-left: 0;
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

    .icon img {
        border-radius: 50%;
        height: 60px;
        width: 60px;
        margin-top: 10px;
        margin-bottom: 10px;
        object-fit: cover;
        line-height: 80px;
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

    .login p {
        text-align: center;
        color: black;
    }

    .login form {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .login form input[type="submit"] {
        width: 100%;
        padding: 15px;
        margin-top: 0px;
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

</script>

<header>
    <nav class="collumn" style="min-width: 800px; max-width: 1200px; margin: auto; display: flex;">
        <img src="/media/ShmelLogo.jpg" alt="Shmel"
            style="height: 50px; margin-top: auto; margin-bottom: auto; display: block;"></img>

        <a class="timer"> 00:00:00 </a>

        <div class="icon" style=" position: relative; margin-left: auto; margin-right: 0; display: block;">
            <img src="<?php echo $profilePicture; ?>" onclick="CollapseLoggin(this);"></img>
            <div class="login">
                <p><a href="/">Main</a></p>
                <p><a href="/profile">Profiel</a></p>
                <p><a href="/files">Files</a></p>
                <form action="/postEvents.php?FUNC=LogOut" method="post">
                    <input type="submit" value="Log Out">
                </form>
            </div>
        </div>
    </nav>
</header>