<?php
require 'vendor/autoload.php';

use setasign\Fpdi\Fpdi;

include_once 'Database.php';

if (isset($_GET['id'])) {
    $clientID = $_GET['id'];

    $sql = "SELECT * FROM liste_fourniseur_client WHERE ID = ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$clientID]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        die("Client introuvable.");
    }
} else {
    die("ID client manquant.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientID = $_POST['ClientID'];
    $n_devis = $_POST['N_Devis'];
    $n_bl = $_POST['N_BL'];
    $n_facture = $_POST['N_Facture'];
    $montantHT = (float)$_POST['Montant_HT'];
    $tva = 20 ;
  


    $montantTTC = $montantHT + ($montantHT * $tva / 100);

    $pdf = new FPDI();
    $pdf->AddPage();
    $pdf->setSourceFile("exemple devis.pdf");
    $template = $pdf->importPage(1);
    $pdf->useTemplate($template);

        $pdf->SetFont('Helvetica');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFontSize(10);

       
        $pdf->SetXY(150, 65);
        $pdf->Write(0, $client['CodeClient']); 

        
        $pdf->SetXY(150, 72);
        $pdf->Write(0, $n_devis);

        
        $pdf->SetXY(150, 79);
        $pdf->Write(0, date('d/m/Y')); 

        
        $pdf->SetXY(150, 86);
        $pdf->Write(0, $n_facture);

     
        $pdf->SetXY(160, 185);
        $pdf->Write(0, number_format($montantHT, 2) . ' DH');

      
        $pdf->SetXY(160, 190);
        $pdf->Write(0, '20%');

        
        $pdf->SetXY(160, 198);
        $pdf->Write(0, number_format($montantTTC, 2) . ' DH');

       
        $pdf->SetXY(65, 240);
        $pdf->Write(0, date('d/m/Y', strtotime('+3 days'))); 

        $filename = 'uploads/facture_' . time() . '.pdf';
        $pdf->Output('F', $filename); 

    // $documentPath = null;
    // if (isset($_FILES['Document']) && $_FILES['Document']['error'] == UPLOAD_ERR_OK) {
    //     $filename = basename($_FILES['Document']['name']);
    //     $destination = 'uploads/' . time() . '_' . $filename;
    //     move_uploaded_file($_FILES['Document']['tmp_name'], $destination);
    //     $documentPath = $destination;
    // }

            $sql = "INSERT INTO liste_facturation 
            (ClientID, N_Devis, N_BL, N_Facture, Montant_HT, TVA, Montant_TTC, Document) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
            $clientID, $n_devis, $n_bl, $n_facture,
            $montantHT, $tva, $montantTTC, $filename
            ]);


    echo "<div class='alert alert-success text-center'>Facture enregistrée avec succès</div>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php include './front/head_front.php'; ?>
    <div class="container py-5">
        <h2 class="text-center mb-4">Ajouter une facture pour le <?= htmlspecialchars($client['Role']) ?> :
            <?= htmlspecialchars($client['NameEntreprise']) ?></h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="ClientID" value="<?= htmlspecialchars($client['ID']) ?>">

            <div class="mb-4 row align-items-center">
                <label for="N_Devis" class="col-sm-4 col-form-label text-end">N° Devis :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                        id="N_Devis" name="N_Devis">
                </div>
            </div>

            <div class="mb-4 row align-items-center">
                <label for="N_BL" class="col-sm-4 col-form-label text-end">N° BL :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0" id="N_BL"
                        name="N_BL">
                </div>
            </div>

            <div class="mb-4 row align-items-center">
                <label for="N_Facture" class="col-sm-4 col-form-label text-end">N° Facture :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                        id="N_Facture" name="N_Facture">
                </div>
            </div>

            <div class="mb-4 row align-items-center">
                <label for="Montant_HT" class="col-sm-4 col-form-label text-end">Montant HT :</label>
                <div class="col-sm-8">
                    <input type="number" step="0.01"
                        class="form-control rounded-pill bg-secondary bg-opacity-25 border-0" id="Montant_HT"
                        name="Montant_HT">
                </div>
            </div>


            <div class="row mt-5">
                <div class="col-12 text-center">
                    <button type="submit" class="btn rounded-pill px-5"
                        style="background-color: #4f57c7; color: white;">
                        Generer la facture
                    </button>
                </div>
            </div>
        </form>

    </div>

</body>

</html>