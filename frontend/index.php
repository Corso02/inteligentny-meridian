<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ovládanie Meridian</title>
    <link rel="stylesheet" href="style/common_styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./scripts/index.js" defer></script>
</head>
<body>
    <!-- admin:adminHeslo          test:pouzivatel -->
    
    <?php
        //error_reporting(E_ERROR | E_WARNING | E_PARSE);
        require_once("./header.php");
        //session_start()
    ?>
    <main>
        <div class="data_from_grafana">
            <div class="measurment">
                <h4>Aktuálna teplota</h4>
                <p>28°C</p>
            </div>
            <div class="measurment">
                <h4>Aktuálna intezita svetla</h4>
                <p>XYZ</p>
            </div>
            <div class="measurment">
                <h4>Naposledy sa dvere otvorili:</h4>
                <p>5.1.2024</p>
            </div>
        </div>
        <?php
            if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']){
                if(isset($_SESSION['is_admin'])){
                    if($_SESSION['is_admin']){
                        echo "Prihlaseny jak admin";
                    }
                    else{
                        echo "Prihlaseny jak pouzivatel";
                    }
                }
            }
            else{
                echo "Nie si prihlaseny";
            }
        ?>
    </main>
    <?php
        require_once("./footer.php")
    ?>
</body>
</html>