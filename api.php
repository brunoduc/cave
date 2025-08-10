<?php session_start();

$datas_dir = './datas';
if (!is_dir($datas_dir)) {
    if (!mkdir($datas_dir, 0770)) { // Crée le répertoire avec permissions 0770.
        echo('Impossible de créer le dossier datas. Créez le manuellement, ou vérifiez les droits d\'écriture.');
    } else {
        echo('Dossier "datas" créé avec succès');
    }
}

class MyCave extends SQLite3 {
    function __construct() {
        $file = './datas/cave.db';
        if (!file_exists($file)) {
            try {
                touch ("./datas/cave.db");
                $this->open("$file",SQLITE3_OPEN_READWRITE);
                $cmd = "CREATE TABLE IF NOT EXISTS cave (id INTEGER PRIMARY KEY AUTOINCREMENT, nom TEXT NOT NULL, region TEXT, annee DATE NOT NULL, producteur TEXT NOT NULL, origine TEXT, qte INTEGER NOT NULL)";
                $this->exec("$cmd");
                $this->close();
            } catch (Exception $e) {
                echo "Impossible de créer la base de données (Droits ?): " . $e->getMessage();
            }
        }
        if (is_writable($file) and ((isset($_SESSION['connected']) AND $_SESSION['connected']))) {

            try {
                $this->open("$file",SQLITE3_OPEN_READWRITE);
                // echo "Connecté à la base de données avec succès !";
            } catch (Exception $e) {
                echo "Impossible de se connecter à la base de données : " . $e->getMessage();
            }
        } else {
            try {
                $this->open("$file",SQLITE3_OPEN_READONLY);
                // echo "Connecté à la base de données (en lecture) avec succès !";
            } catch (Exception $e) {
                echo "Impossible de se connecter à la base de données : " . $e->getMessage();
            }
        }
    }
}

$cave = new MyCave();

function clean($data) {
    $result = htmlspecialchars("$data", ENT_QUOTES);
    $result = trim($result);
    $result = ucwords(strtolower($result));
    return $result;
}

switch ($_GET["action"]) {
    case "get_posts":
        echo <<<EOF
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" class="nb np"></th>
                    <th scope="col">Nom</th>
                    <th scope="col" class="w12e">Région</th>
                    <th scope="col" class="w4e">Année</th>
                    <th scope="col" class="w16e">Producteur</th>
                    <th scope="col" class="w12e">Origine</th>
                    <th scope="col" class="w4e">Qté</th>
                    <th class="nb">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
        EOF;

        if (isset($_SESSION['connected']) AND $_SESSION['connected']) {
            echo <<<EOF
                <tr>
                <form hx-get="./api.php?action=create_post" data-hx-target=".posts" data-hx-swap="beforeend" data-hx-encoding="multipart/form-data">
                    <td class="nb np"><input type="hidden" name="id" /></td>
                    <td><input type="text" name="nom" /></td>
                    <td><input type="text" name="region" /></td>
                    <td><input type="text" name="annee" /></td>
                    <td><input type="text" name="producteur" /></td>
                    <td><input type="text" name="origine" /></td>
                    <td><input type="text" name="qte" /></td>
                    <td class="nb text-center"><button class='btn btn-success add'>Ajouter</td>
                </form>
                </tr>
        EOF;
        }
        $results = $cave->query("SELECT * FROM cave WHERE qte > 0 ORDER BY region,nom");
        while ($row = $results->fetchArray()) {
            echo "<tr data-hx-target='this' id='id-$row[id]'>";
            echo "<td class=\"nb np\"></td><td>$row[nom]</td><td>$row[region]</td><td>$row[annee]</td><td>$row[producteur]</td><td>$row[origine]</td><td class='text-center'>$row[qte]</td>";

            if (isset($_SESSION['connected']) AND $_SESSION['connected']) {
            echo "<td class='w12e nb text-center'>
                <button class='btn btn-info' hx-confirm='Êtes vus sûr ?' data-hx-get='./api.php?action=update_post&id=$row[id]' title='Enlever une bouteille'><img alt='Enlever une bouteille' src='/css/delete one.svg'/></button>
                <button href=\"#\" class=\"btn btn-info\" data-hx-get='./edit.php?id=$row[id]&nom=$row[nom]&region=$row[region]&origine=$row[origine]&annee=$row[annee]&producteur=$row[producteur]&qte=$row[qte]' title='Modifier'><img alt='Modifier' src='/css/correct.svg'/></button>
            </td>
            ";
            }
            echo "</tr>";
        }
        echo "</tbody>";
        break;
    case "create_post":
        header("HX-Trigger:create_post");
        $nom = clean($_GET["nom"]);
        $region = clean($_GET['region']);
        $annee = intval($_GET['annee']);
        $producteur = clean($_GET['producteur']);
        $origine = clean($_GET['origine']);
        $qte = $_GET['qte'];
        $cave->query("INSERT INTO cave (nom,region,annee,producteur,origine,qte) VALUES ('$nom','$region','$annee','$producteur','$origine','$qte')");
        break;
    case "update_post":
        header("HX-Trigger:update_post");
        $id = $_GET["id"];
        $sql ="UPDATE cave SET qte = qte-1 where id=$id";
        $cave->query($sql);
        break;
    case "modify_post":
        header("HX-Trigger:modify_post");
        $id = $_GET["id"];
        $nom = clean($_GET["nom"]);
        $region = clean($_GET['region']);
        $annee = intval($_GET['annee']);
        $producteur = clean($_GET['producteur']);
        $origine = clean($_GET['origine']);
        $qte = $_GET['qte'];
        $sql = "UPDATE cave SET nom = '$nom', region = '$region', annee = '$annee', producteur = '$producteur', origine = '$origine', qte = $qte WHERE id = $id";
        $cave->query($sql);
        break;
    case "modif_post":
        header("HX-Trigger:modif_post");
        $id = $_GET["id"];
        $row = $cave->querySingle("SELECT * FROM cave WHERE id='$id'", true);


echo <<<EOF
<form data-hx-get="./api.php?action=create_post" data-hx-target=".posts" data-hx-swap="beforeend" data-hx-encoding="multipart/form-data">
<td><input type="hidden" name="id"></td>
<td><input type="text" name="nom" value="$row[nom]" /></td>
<td><input type="text" name="region" value="$row[region]" /></td>
<td><input type="text" name="annee" style="width:4em" value="$row[annee]" /></td>
<td><input type="text" name="producteur" value="$row[producteur]" /></td>
<td><input type="text" name="origine" value="$row[origine]" /></td>
<td><input type="text" name="qte" style="width:3em;" value="$row[qte]" /></td>
<td><button class="btn btn-success"> Valider </td>
</form>
EOF;




        break;
    default:
        echo "la yomkin";
}
