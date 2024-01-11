<header>
    <h1>Meridian IoT</h1>
    <?php 
        session_start();
    ?>
    <p id=<?php echo((isset($_SESSION['logged_in']) && $_SESSION['logged_in']) ? 'logout_btn' : 'login_btn')?> >
        <?php echo((isset($_SESSION['logged_in']) && $_SESSION['logged_in']) ? "Odhlásiť sa" : "Prihlásiť sa") ?>
    </p>
</header>