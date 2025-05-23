<?php
if (isset($_GET['file'])) {
    $filename = basename($_GET['file']);
    $filepath = 'uploads/' . $filename;

    if (file_exists($filepath)) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    } else {
        echo "Fichier introuvable.";
    }
} else {
    echo "Paramètre manquant.";
}
?>