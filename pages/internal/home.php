<?php include 'pages/internal/header.php'; ?>

<style>

.collumn{
    min-width: 40vw;
    max-width: 800px;
    margin: auto;
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
  background-image: url("/media/parallax-4.png");

  min-height: 700px;
}

#parallax2{
  background-image: url("/media/parallax-5.png");

  min-height: 800px;
}

#parallax3{
    background-image: url("/media/parallax-6.png");

    min-height: 800px;
}

.parallaxCentered{
    position: absolute;
    top: 30%;
    color: white;
}
</style>

<main>

<div class="parallax" id="parallax1">
    <div class="collumn">
        <div class="parallaxCentered">
            <h1 style="font-size: 2em; margin-bottom: 0px;">Welkom terug:</h1>
            <h1 style="font-size: 5em; margin-top: 0px;"><?php echo $_SESSION['username'];?></h1>
        </div>
    </div>
</div>

<div class="collumn" style="word-wrap: break-word; margin-top:10px; margin-bottom:10px;"><br>

</div>

<div class="parallax" id="parallax2">
    <div class="collumn">
        <div class="parallaxCentered">
            <h1 style="font-size: 2em; margin-bottom: 0px;">Help jij mee?</h1>
            <h1 style="font-size: 5em; margin-top: 0px;">Samen de natuur vervuilen</h1>
        </div>
    </div>
</div>

<div class="collumn" style="word-wrap: break-word; margin-top:10px; margin-bottom:10px;"><br>

</div>

<div class="parallax" id="parallax3">
    <div class="collumn">
        <div class="parallaxCentered">
            <h1 style="font-size: 2em; margin-bottom: 0px;">Shmel</h1>
            <h1 style="font-size: 5em; margin-top: 0px;">Vervuilt de natuur, want waarom niet?</h1>
        </div>
    </div>
</main>

<?php include 'pages/footer.php';