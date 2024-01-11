<?php
    require_once("./Database.php");
    $db = new Database("./database.db");
    session_start();

    if(isset($_POST['logout']) && $_POST['logout']){
        $_SESSION['logged_in'] = false;
        $_SESSION['is_admin'] = false;
    }

    if(isset($_POST['login']) && $_POST['login']){
        $res = $db->check_user_login($_POST['name'], hash("sha256", $_POST['password']));
        if($res['success']){
            $_SESSION['logged_in'] = true;
            $_SESSION['is_admin'] = $res['is_admin'];
        }
        echo json_encode($res);
    } 

    $file = fopen("./log.txt", "a");
    $req = "pozadiavka prijata\n";
    $data = http_build_query($_POST) . "\n";
    fwrite($file, $req);
    fwrite($file, $data);
    fclose($file);

    if(isset($_POST['get_pref']) && $_POST['get_pref']){
        $file = fopen("./log.txt", "a");
        $req = $_POST['get_pref'] . " " . $_POST['card_id'] . ".\n";
        fwrite($file, $req);
        fclose($file);
        $res = $db->get_preferences(($_POST['card_id']));
        echo json_encode($res);
    }

?>