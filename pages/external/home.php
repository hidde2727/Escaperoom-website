<?php include 'pages/external/header.php'; ?>

<style>

.collumn{
    min-width: 610px;
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
  background-image: url("/media/parallax-1.png");

  min-height: 700px;
}

#parallax2{
  background-image: url("/media/parallax-2.png");

  min-height: 800px;
}

#parallax3{
    background-image: url("/media/parallax-3.png");

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
            <h1 style="font-size: 2em; margin-bottom: 0px;">Shmel</h1>
            <h1 style="font-size: 5em; margin-top: 0px;">Goed voor de natuur</h1>
        </div>
    </div>
</div>

<div class="collumn" style="word-wrap: break-word; margin-top:10px; margin-bottom:10px;"><br>

</div>

<div class="parallax" id="parallax2">
    <div class="collumn">
        <div class="parallaxCentered">
            <h1 style="font-size: 2em; margin-bottom: 0px;">Onze brandstof</h1>
            <h1 style="font-size: 5em; margin-top: 0px;">Uw auto volledig groen</h1>
        </div>
    </div>
</div>

<div class="collumn" style="word-wrap: break-word; margin-top:10px; margin-bottom:10px;"><br>

</div>

<div class="parallax" id="parallax3">
    <div class="collumn">
        <div class="parallaxCentered">
            <h1 style="font-size: 2em; margin-bottom: 0px;">Shmel</h1>
            <h1 style="font-size: 5em; margin-top: 0px;">Vervuilt absoluut de natuur niet!</h1>
        </div>
    </div>
</main>

<?php include 'pages/footer.php';