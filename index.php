<?php
session_start();
require 'model/user.php';
require 'model/tim.php';



//UPDATE
if (isset($_POST["azurirajTim"])) {
    for ($i = 0; $i < count($_SESSION["timovi"]); $i++) {
        if ($_SESSION["timovi"][$i]["timID"] == $_GET["timID-izmeni"]) {
            $_SESSION["timovi"][$i]["nazivTima"] = $_POST["nazivTima"];
            $_SESSION["timovi"][$i]["drzava"] = $_POST["drzava"];
            $_SESSION["timovi"][$i]["godinaOsnivanja"] = $_POST["godinaOsnivanja"];
            $_SESSION["timovi"][$i]["brojTitula"] = $_POST["brojTitula"];
            break;
        }
    }
    header("Location: .");
    exit();
}

//sort

if (isset($_GET["sortiraj"])) {
    usort($_SESSION['timovi'], "sortiranje");
    header("Location:.");
    exit();
}
//search
if (isset($_GET["unos"])) {
    if ($_GET["unos"] == "") {
        unset($_SESSION["timovi-search"]);
    } else {
        $_SESSION["timovi-search"] = [];
        foreach ($_SESSION["timovi"] as $tim) {
            if (
                str_contains(strtolower($tim["nazivTima"]), strtolower($_GET["unos"])) ||
                str_contains(strtolower($tim["drzava"]), strtolower($_GET["unos"])) ||
                str_contains(strtolower($tim["godinaOsnivanja"]), strtolower($_GET["unos"])) ||
                str_contains(strtolower($tim["brojTitula"]), strtolower($_GET["unos"]))
            ) {
                $_SESSION["timovi-search"][] = $tim;
            }
        }
    }
}



if (isset($_POST['dodajTim'])) {
    $noviTim = array(
        "timID" => findMaxId() + 1,
        "nazivTima" => $_POST['nazivTima'],
        "drzava" => $_POST['drzava'],
        "godinaOsnivanja" => $_POST['godinaOsnivanja'],
        "brojTitula" => $_POST['brojTitula'],
    );
    $_SESSION['timovi'][] = $noviTim;
    header("Location: .");
    exit();
}


// DELETE
if (isset($_GET['timID-izbrisi']) && is_numeric($_GET['timID-izbrisi'])) {
    for ($i = 0; $i < count($_SESSION['timovi']); $i++) {
        if ($_GET['timID-izbrisi'] == $_SESSION['timovi'][$i]['timID']) {
            array_splice($_SESSION['timovi'], $i, 1);
            header("Location: .");
            exit();
        }
    }
}
//UPDATE

if (isset($_GET["timID-izmeni"])) {
    include "updateTeam.php";
    exit();
}



function findMaxId()
{
    // $idjevi = [];
    // foreach($_SESSION['timovi'] as $tim) {
    //     $idjevi[] = $tim['timID'];
    // }
    // return max($idjevi);
    $max = 0;
    foreach ($_SESSION['timovi'] as $tim) {
        if ($tim['timID'] > $max) {
            $max = $tim['timID'];
        }
    }
    return $max;
}


if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    foreach ($korisnici as $k) {
        if ($k['username'] == $username && $k['password'] == $password) {
            $_SESSION['username'] = $username;
            // header("Location: home.php");
            include 'home.php';
            exit();
        }
    }

    // if(login($username, $password)) {
    //     $_SESSION['username'] = $username;
    //     header("Location: home.php");
    //     exit();
    // }
}

if (isset($_GET['addTeam'])) {
    include 'addTeam.php';
    exit();
}

if (isset($_SESSION['username'])) {
    include 'home.php';
    exit();
}

include 'login.php';

function login($username, $password)
{
    global $korisnici;
    foreach ($korisnici as $k) {
        if ($k['username'] == $username && $k['password'] == $password) {
            return true;
        }
    }
    return false;
}
//funkcija za sortiranje
function sortiranje($data1, $data2)
{
    return strcmp(strtolower($data1["nazivTima"]), strtolower($data2["nazivTima"]));
}
