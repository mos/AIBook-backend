<?php 
    session_start();
    class state{
        
        function setSession($name,$value){
            $_SESSION["$name"] = $value;
        }
        function getSession($name){
            return $_SESSION["$name"];
        }
        function delSession($name){
            unset($_SESSION["$name"]);
        }
        function setCookie($name,$value){
            setcookie($name,$value);
        }
        function getCookie($name){
            return $_COOKIE["$name"];
        }
    }

?>