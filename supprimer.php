<?php
include_once 'Database.php';
if(!isset($_GET['id'])) {
    header('Location: liste_fourniseur.php');
    exit();
}
$id = $_GET['id'];
$sql = "DELETE FROM liste_fourniseur_client WHERE ID = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);   
$stmt->execute();
if($stmt->rowCount() > 0) {
    echo "<script>alert('Fournisseur supprimé avec succès');</script>";
} else {
    echo "<script>alert('Erreur lors de la suppression du fournisseur ');</script>";
}
if (isset($_SERVER['HTTP_REFERER'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    header('Location: liste_fourniseur.php');
}
?>