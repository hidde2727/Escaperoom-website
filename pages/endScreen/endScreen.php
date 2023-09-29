<style>

    .timer {
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

    .timer:hover {
        background-color: #ba3271;
        transition: background-color 0.2s;
    }

</style>
<main>

<h1 class="timer">00:00:00</h1>

<p>GOED GEDAAN</p>
<p>Jullie hebben de escaperoom voltooid</p>

<script>

function UpdateTimer() {
    var time = new Date(<?php echo $endTime + $connection->GetExtraTime($currentClass); ?>);
    time = time.toISOString().slice(11, 19);
    var item = document.getElementsByClassName("timer");
    item[0].textContent = time;
}

UpdateTimer();

</script>