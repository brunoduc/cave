<?php
$id = $_GET['id'];
$nom = $_GET['nom'];
$region = $_GET['region'];
$annee = $_GET['annee'];
$producteur = $_GET['producteur'];
$origine = $_GET['origine'];
$qte = $_GET['qte'];
?>

        <td><input type="hidden" name="id" value="<?php echo $id; ?>" ></td>
        <td><input type="text" name="nom" value="<?php echo $nom; ?>" /></td>
        <td><input type="text" name="region" value="<?php echo $region; ?>" /></td>
        <td><input type="text" name="annee" value="<?php echo $annee; ?>" /></td>
        <td><input type="text" name="producteur" value="<?php echo $producteur; ?>" /></td>
        <td><input type="text" name="origine" value="<?php echo $origine; ?>" /></td>
        <td><input type="text" name="qte" value="<?php echo $qte; ?>" /></td>
        <td><button  class="btn btn-danger"
            hx-get="./api.php?action=modify_post"
            hx-include="closest tr"
            hx-target="#id-<?php echo $id ?>" >
                OK
            </button>&nbsp;<button class="btn btn-primary" data-hx-get="./api.php?action=get_posts" data-hx-target=".posts">
            Annuler
            </button>
        </td>

