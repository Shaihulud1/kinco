<?php

         try{
            $pdo = new PDO("mysql:dbname=kin;host=127.0.0.1:3307",  "root", "12",array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        } catch (PDOException $e) {
            
             echo "DATABASE ERROR : ".$e->getMessage();
             exit;            
            }
        


    

