<?php

include 'mysql/connectionInfo.php';
    class MySQLHandler{

        private $connection;

        function Querry($string){
            $result = $this->connection->query($string);
            if ($result === FALSE) {
                echo "MYSQL error: " . $this->connection->error;
            }
            else{
                return $result;
            }
        }

        function __construct(){
            global $servername, $username, $password, $database;
            $this->connection = new mysqli($servername, $username, $password, $database);

            if ($this->connection->connect_error) {
                exit("Connection failed: " . $this->connection->connect_error);
            }

            // create table if it doesn't exist already
            $this->Querry("
            CREATE TABLE IF NOT EXISTS classInfo (
                className VARCHAR(2) PRIMARY KEY NOT NULL,

                extraTime INT DEFAULT 0,
                logginAtemptsRemaining INT DEFAULT 3,
                lastLogginAtempt BIGINT DEFAULT 0,

                endTime BIGINT DEFAULT 0
            );");

            $this->Querry("
            INSERT IGNORE INTO classInfo(className)
            VALUES('3A'), ('3B'), ('3C'), ('3D'), ('3E'), ('3F'), ('3G')
            ;");

            // create table if it doesn't exist already
            $this->Querry("
            CREATE TABLE IF NOT EXISTS logginAttempts (
                id BIGINT PRIMARY KEY AUTO_INCREMENT,
                className VARCHAR(2) NOT NULL,

                username TINYTEXT DEFAULT 0,
                password TINYTEXT DEFAULT 0,
                time TIME DEFAULT CURRENT_TIME
            );");
        }
        function __destruct(){
            $this->connection->close();
        }

        function GetClassInfo($infoName, $ClassName){
            if($ClassName === '3#'){
                return 0;
            }
            return $this->Querry("SELECT $infoName FROM classInfo WHERE className = '$ClassName';")->fetch_assoc()[$infoName];
        }

        function GetExtraTime($ClassName){
            return $this->GetClassInfo('extraTime', $ClassName);
        }

        function GetLoginAtemptsRemaining($ClassName){
            return $this->GetClassInfo('logginAtemptsRemaining', $ClassName);
        }

        function GetLastLoginAtempt($ClassName){
            return $this->GetClassInfo('lastLogginAtempt', $ClassName);
        }

        function GetEndTime($ClassName){
            return $this->GetClassInfo('endTime', $ClassName);
        }

        function SetLoginAttemptsRemaining($ClassName, $Amount){
            $this->Querry("
            update classInfo
            set
            logginAtemptsRemaining= $Amount
            WHERE className='$ClassName'");
        }

        function UseLoginAttempt($ClassName){
            $this->Querry("
            update classInfo
            set
            logginAtemptsRemaining= logginAtemptsRemaining - 1
            WHERE className='$ClassName'");
        }

        function SetLastLogginAtempt($ClassName, $LastAttempt){
            $this->Querry("
            update classInfo
            set
            lastLogginAtempt= $LastAttempt
            WHERE className='$ClassName'");
        }

        function SetEndTime($ClassName, $Time){
            $this->Querry("
            update classInfo
            set
            endTime= $Time
            WHERE className='$ClassName'");
        }
        
        function AddLoginAttempt($ClassName, $Username, $Password){
            $this->Querry("
            INSERT IGNORE INTO logginAttempts(className, username, password)
            VALUES ('$ClassName', '$Username', '$Password')
            ;");
        }

    }

?>