<style>

    body{
        background-image: url("/media/ShmelBackground.jpg");
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    main{
        width: 100%;
        height: 100%;
    }

    .row {
        display: grid;
        grid-template-columns: 33% 33% 33%;
        grid-template-rows: auto;
        justify-content: space-evenly;
        width: 80%;
        margin: 200px auto;
    }

    .column {
        background-color: white;
        text-align: center;
        color: black;
        margin: 20px;
    }
    .column h1 {
        text-align: center;
        color: black;
        font-size: 24px;
        padding: 20px 0 20px 0;
        border-bottom: 1px solid #dee0e4;
    }

    .column table{
        padding-left: 20px;
    }

</style>

<?php include 'pages/header.php';?>

<main>
    <div class="row">
        <div class="column">
            <h1>Profiel:</h1>
            <table>
                <tr>
                    <td style="text-align: right;"><a>Naam</a></td>
                    <td style="text-align: center;" width=40px><a>-</a></td>
                    <td style="text-align: left;"><a> <?php echo $connection->getUserInfo($_SESSION['username'], "name"); ?> </a></td>
                </tr>
                <tr>
                    <td style="text-align: right;"><a>GeboorteDatum</a></td>
                    <td style="text-align: center;" width=40px><a>-</a></td>
                    <td style="text-align: left;"><a> <?php echo $connection->getUserInfo($_SESSION['username'], "birthday"); ?> </a></td>
                </tr>
                <tr>
                    <td style="text-align: right;"><a>Hobbys</a></td>
                    <td style="text-align: center;" width=40px><a>-</a></td>
                    <td style="text-align: left;"><a> <?php echo $connection->getUserInfo($_SESSION['username'], "hobbys"); ?> </a></td>
                </tr>
                <tr>
                    <td style="text-align: right;"><a>Mail</a></td>
                    <td style="text-align: center;" width=40px><a>-</a></td>
                    <td style="text-align: left;"><a> <?php echo $connection->getUserInfo($_SESSION['username'], "mail"); ?> </a></td>
                </tr>
                <tr>
                    <td style="text-align: right;"><a>Telefoonnummer</a></td>
                    <td style="text-align: center;" width=40px><a>-</a></td>
                    <td style="text-align: left;"><a> <?php echo $connection->getUserInfo($_SESSION['username'], "phonenumber"); ?> </a></td>
                </tr>
                <tr>
                    <td style="text-align: right;"><a>Functie</a></td>
                    <td style="text-align: center;" width=40px><a>-</a></td>
                    <td style="text-align: left;"><a> <?php echo $connection->getUserInfo($_SESSION['username'], "function"); ?> </a></td>
                </tr>  
            </table>
        </div>
        <div class="column">
            <h1>Agenda:</h1>
        </div>
        <div class="column">
            <h1>Files:</h1>
        </div>
    </div>
</main>