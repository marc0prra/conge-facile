<?php

require_once("include/config_bdd.php");
require_once("include/user.php");

if (empty($user)) {
    header("Location: connexion.php");
} else {
    header("Location: homePage.php");
}