<?php
/**
 * Created by PhpStorm.
 * User: sstienface
 * Date: 04/12/2018
 * Time: 11:25
 */

// Premiere ligne

echo "<h1>Fiche des eleves</h1>";

$servername = "localhost";
$username = "id7331244_spilmontandre";
$password = "";
$dbname = "id7331244_basetest";

$click =0;

$conn = new mysqli($servername,$username,$password);

if($conn ->connect_error){
    die("Connection failed: " . $conn->connect_error);
}else{
// Selectionner la base à utiliser
    $conn->select_db($dbname);
}



function eleves($nom, $prenom, $age)
{
    global $conn;

    $sql = "INSERT INTO eleves VALUES ('','$prenom','$nom','$age')";

    $conn ->query($sql);


    if($conn->query($sql) === TRUE){
        echo "Les enregistrements ont été ajouté";
    }else{
        die("ERREUR: " . $conn->error);
    }
}




function afficher(){
global $conn;

 $sql ="SELECT * FROM eleves";

    $result = $conn->query($sql);
    while($row = $result->fetch_assoc())
    {
        echo $row["id"].".".$row['nom']." ".$row['prenom']." ".$row["age"]."<br>";
    }
}




function update($id,$nom,$prenom,$age){

    global $conn;

    $sql = "UPDATE eleves set nom = '$nom', prenom = '$prenom', age = '$age' where id = $id";
    $conn->query($sql);


}

function delete($id){
    global $conn;

 $sql = "DELETE FROM eleves WHERE id= $id";
    $conn->query($sql);


}

?>

<form action="index.php" method="get">
    <fieldset>
        <legend>nouvelle eleves</legend>
   <input type="text" name="nom" placeholder="entrer un nom">
   <input type="text" name="prenom" placeholder="entrer un prenom">
    <input type="text" name="age" placeholder="entrer votre age">
    <input type="submit" name="envoyer" value="envoyer">
    </fieldset>
</form>


<?php





function formulaireEleve(){

    global $conn;
    $nom = $_GET['nom'];
    $prenom = $_GET['prenom'];
    $age = $_GET['age'];




    if($_GET["nom"] !="" && $_GET['prenom'] != "" && $_GET['age']!=""  ) {


        eleves($nom, $prenom, $age);

    }
else{
        echo "veuillez entrez des information";
    }
}
formulaireEleve();
?>
<hr>
<form action="index.php" method="get">
    <input type="submit" name="afficher" value="afficher la liste">
</form>

<?php
if(!empty($_GET['afficher'])) {
    afficher();
}
echo "<hr>";
?>

<form action="index.php" method="get">
    <fieldset>
        <legend>suprimer un eleves</legend>
        <input type="text" name="suprimer" placeholder="entrer ID de l'eleve a supprimer">
        <input type="submit" name="ids" value="suprimer">
    </fieldset>
</form>

<?php




echo "<hr>";

?>
<form action="index.php" method="get">
    <fieldset>
        <legend>modifier les information des eleves</legend>
        <input type="text" name="id1" placeholder="ID de l'eleve a modifier">
        <input type="text" name="nom1" placeholder="nom de l'eleve a modifier">
        <input type="text" name="prenom1" placeholder="prenom de l'eleve a modifier">
        <input type="text" name="age1" placeholder="ages de leleve a modifier">
        <input type="submit" name="modifier" value="modifier">
    </fieldset>
</form>
<?php


    update($_GET["id1"], $_GET["nom1"], $_GET["prenom1"], $_GET["age1"]);

delete($_GET["suprimer"]);

echo "<hr><hr>"
?>
<h1>Fiche Mugs</h1>

<?php
function mugs($description){
    global $conn;

    $sql= "INSERT INTO mugs VALUES ('','$description')";

    $conn ->query($sql);
}

if (!empty($_GET["mugs"])){
mugs($_GET['mugs']);
}

?>
<form action="index.php" method="get">
    <input type="text" name="mugs">
    <input type="submit" name="ajouterMugs" value="ajouter un mugs">
</form>

<?php

function associer($idEleve, $idMugs){

    global $conn;

    $sql="INSERT INTO eleves_mugs value ('',$idEleve,$idMugs)";

    $conn->query($sql);

}


function updateMugs($idMugsM,$description){

    global $conn;

    $sql = "UPDATE mugs set description = '$description' where id = $idMugsM";

    $conn->query($sql);

}
?>
<form action="index.php" method="get">
    <input type="text" name="elevesMugs">
    <input type="submit" name="assossier" value="assossier">
</form>

<?php
function afficherassosiation($ideleves){
    global $conn;


    $sql ="SELECT * FROM eleves where id = $ideleves";

    $result = $conn->query($sql);
    while($row = $result->fetch_assoc())
    {
        echo $row["nom"]." ".$row["prenom"]." a ";
    }


    $sql ="SELECT mugs.description FROM eleves_mugs,mugs WHERE eleves_mugs.id_eleves = $ideleves and eleves_mugs.id_mugs = mugs.id";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc())
    {
        echo $row["description"].",";
    }



}

afficherassosiation($_GET["elevesMugs"]);






