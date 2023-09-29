<style>

.collumn{
    min-width: 610px;
    max-width: 800px;
    margin: auto;
    margin-left: 2%;
    user-select: none;
  }

.collumn h1,h2,h3,h4,h5,h6{
    white-space: nowrap;
}

.parallax{
  position: relative;

  background-attachment: fixed;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}

#parallax1{
  background-image: url("/media/parallax-1.png");

  height: 100vh;
}

.parallaxCentered{
    position: absolute;
    top: 10%;
    color: white;
}
</style>

<main>

<div class="parallax" id="parallax1">
    <div class="collumn">
        <div class="parallaxCentered">
            <h1 style="font-size: 2em; margin-bottom: 0px;">Ga rustig zitten aan een tafel</h1>
            <h1 style="font-size: 7em; margin-top: 0px;">Raak niks aan!</h1>
            <h1 style="font-size: 2em; margin-bottom: 0px;">+15 minuten als je iets aanraakt :(</h1>
            <h1 style="font-size: 2em; margin-bottom: 0px;">Er komt een duidelijk signaal voor wanneer jullie kunnen beginnen</h1>
        </div>
    </div>
</div>

</main>

<script>

function CheckEscaperoomStart() {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "/postEvents.php?FUNC=HasEscaperoomStarted", true);
    xhttp.onload = function(event) {
        if (xhttp.status == 200 && this.responseText == "true") {
            window.location.reload();
        }
    }
    xhttp.send(null);
}
const interval = window.setInterval(CheckEscaperoomStart, 1000);

</script>