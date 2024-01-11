<?php
    class Database extends SQLite3{
        function __construct($pathToDb){
            $this->open($pathToDb);
            if(!$this) {
                echo $this->lastErrorMsg();
             }
        }

        function check_user_login($name, $password){
            $statement = $this->prepare("SELECT * FROM users WHERE user_name = :user_name AND user_password = :pswd");

            $statement->bindValue(":user_name", $name);
            $statement->bindValue(":pswd", $password);

            $res = $statement->execute();

            if(!($user_data = $res->fetchArray(SQLITE3_NUM))){
                return array("success"=>false);
            }
            return array("success"=>true,"is_admin"=>$user_data[3]);
        }

        function get_preferences($cardId){
            $statement = $this->prepare("SELECT user_id FROM users where card_id = :id");

            $statement->bindValue(":id", $cardId);

            $res = $statement->execute();

            if(!($data = $res->fetchArray(SQLITE3_NUM))){
                return array("success"=>false);
            }

            $user_id = $data[0];

            $statement = $this->prepare("SELECT min_temp, max_temp, min_light FROM preferences where user_id = :id");
            $statement->bindValue(":id", $user_id);

            $res = $statement->execute();

            if(!($data = $res->fetchArray(SQLITE3_NUM))){
                return array("success"=>false);
            }
            return array("success"=>true, "min_temp"=>$data[0], "max_temp"=>$data[1], "min_light"=>$data[2]);
        }
    }
?>