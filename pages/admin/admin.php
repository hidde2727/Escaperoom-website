<style>

    .startButton {
        width: 50%;
        padding: 15px;
        margin-top: 20px;
        background-color: #ed4700;
        border: 0;
        cursor: pointer;
        font-weight: bold;
        color: #ffffff;
        transition: background-color 0.2s;
        text-align: center;
        margin-left: auto;
        margin-right: auto;
        margin-top: 0px;
    }

    .startButton:hover {
        background-color: #ba3271;
        transition: background-color 0.2s;
    }

</style>
<main>

<i onclick="LogOut()" class="fa fa-sign-out fa-3x" style="position: fixed; top: 0px; left: 0px; cursor: pointer;"></i>

<?php
    if($generalInfo->started === "true"){
        echo '<h1 class="timer startButton">00:00:00</h1>';
    }
    else if($generalInfo->spinnedUp === "true"){
        echo '<h1 class="startButton" onclick="StartEscaperoom()">!!! - START - !!!</h1>';
    }
    else{
        echo '<h1 class="startButton" onclick="SpinUpEscaperoom()">Spin up (editing forbidden and nice start screen)</h1>';
    }
?>

</main>
<script>

function UpdateTimer() {
        var start = new Date(<?php echo $generalInfo->startTime; ?>);
        var difference = new Date(Date.now() - start);
        var time = difference.toISOString().slice(11, 19);
        var item = document.getElementsByClassName("timer");
        item[0].textContent = time;
    }

window.setInterval(UpdateTimer, 1000);

function SpinUpEscaperoom() {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "/postEvents.php?FUNC=SpinUpEscaperoom", true);
    xhttp.onload = function(event) {
        if (xhttp.status == 200 && this.responseText != "error") {
            window.location.reload();
        }
    }
    xhttp.send(null);
}

function StartEscaperoom() {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "/postEvents.php?FUNC=StartEscaperoom", true);
    xhttp.onload = function(event) {
        if (xhttp.status == 200 && this.responseText != "error") {
            window.location.reload();
        }
    }
    xhttp.send(null);
}

function LogOut(){
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "/postEvents.php?FUNC=StopAdmin", true);
    xhttp.onload = function(event) {
        if (xhttp.status == 200 && this.responseText != "error") {
            window.location.reload();
        }
    }
    xhttp.send(null);
}

</script>