<?php 
include_once 'Database.php';
$sql = "DELETE FROM factures WHERE ID = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_GET['id']]);
if ($stmt->rowCount() > 0) {
    header("Location: Liste_Facturation.php");
    exit;
} else {
    echo "<div class='alert alert-danger'>Erreur lors de la suppression de la facture.</div>";
}

?>