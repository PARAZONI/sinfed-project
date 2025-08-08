<?php
if (!isset($_GET['file'])) {
    die("Fichier non spécifié.");
}

$file = "../users_area/uploads/archives/" . basename($_GET['file']);

if (!file_exists($file)) {
    die("Fichier introuvable.");
}

header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=" . basename($file));
readfile($file);
?>