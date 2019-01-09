Consignes :

+ Vous allez réaliser votre premier CRUD en PHP : CRUD est l'acronyme de Create Read Update Delete
+Tout d'abord, vous allez utiliser PHP et l'extension mySQLi pour vous connecter à votre base de donnée sur webhost
+ Vous selectionnerez votre base de donnée
+ Vous allez créer une fonction en PHP qui va ajouter une personne à la table eleves, cette fonction prendra en parametre
$nom,$prenom, $age

+ A l'intérieur de cette fonction, vous allez executer la requete sql adéquate qui va insérer un nouvel enregistrement
  en utilisant les parametres de votre fonction comme valeurs

+Vous allez créer une fonction qui va selectionner tout les enregistrements de la table eleves et retourner le resultat
+Vous allez faire un appel à cette fonction pour afficher la liste des éléves sur la page

+ Créer une autre fonction permettant de mettre à jour un éléve, cette fonction prendra en parametre $prenom,$nom,$age
   et $idEleve, elle mettre à jour l'éléve ayant pour id $idEleve en utilisant l'instruction SQL adéquate

+ Créer une autre fonction permettant de supprimer un éléve, cette fonction prendra en parametre $idEleve et permettra
   de supprimer l'enregistrement ayant pour id $idEleve en utilisant l'instruction SQL adéquate.

BONUS : Créer toute la partie front qui affichera un formulaire permettant d'ajouter un éléve, un lien permettant d'afficher
   la liste des éléves, un lien permettant de supprimer un éléve, un lien permettant de mettre à jour un éléve.

BONUS2 : Permettre d'ajouter des mugs depuis le front, permettre d'associer un mug à un éléve, permettre de modifier un mug,
 créer une fonction qui retourne les éléves et les mugs que ceux ci possédent.





Théorie :

Les bases de données servent à stocker des données de façon permanente.

Elles sont nécessaires à la majorité des applications web et sont même parfois utilisées dans des applications pc.

Lorsque vous publiez du contenu sur votre réseau social préféré, ces données sont stockées dans une base de donnée,
un message que vous envoyez est une donnée, votre age est une donnée etc...

Le fait de stocker ces données vous permet de les retrouver lorsque vous vous rendez sur le site, mais il vous permet
également de consulter le contenu publié par d'autres personnes.

PHP est étroitement lié aux bases de données et il est trés facile de communiquer avec une base de donnée, d'y insérer des
données etc...


A ce jour, PHP offre deux extensions pour manipuler les bases de données : MySQLi et PDO

Pour des raisons pratiques, nous aborderons uniquement MySQLi car l'extension est installée par défaut avec php.


Avant de pouvoir travailler avec notre base de donnée, il nous faut établir une connection avec celle ci.

Voici le script permettant de le faire :

$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "nom de la base de donnee";

// Créer la connection
$conn = new mysqli($servername, $username, $password);

// Verifier qu'il n'y a pas eu d'erreurs lors de la connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else
{
// Selectionner la base à utiliser
$conn->select_db($dbname);
}


Pour vous connecter une base de donnée, il faut connaitre quatres informations :

- le nom d'hôte de la base de donnée ( $servername ) : On utilise généralement localhost, cela permet d'utiliser le serveur sql où le script
php est éxécuté.

Il est possible d'accéder à une base de donnée si celle ci l'autorise, on utilisera alors l'adresse ip du serveur ou son
nom de domaine.


- L'utilisateur de la base de donnée : voir sur 000webhost

- Le mot de passe de l'utilisateur de la base de donnée : voir sur 000webhost

- Le nom de la base de donnée à utiliser : voir sur  000webhost



Insérer des données dans une base de donnée :

Il est possible d'insérer des données dans une table, généralement on crée les tables manuellement puis on les utilise
avec PHP.

La syntaxe sql est la suivante : INSERT INTO table_name (column1, column2, column3,...)
                                 VALUES (value1, value2, value3,...)

Il est possible de ne pas préciser les noms de collones si on prévoit d'insérer des données sur l'ensemble des collones de
                                 la table.



Exemple :

$sql = "INSERT INTO mugs VALUES ('','Un mug noir')";

$conn->query($sql);

Je viens d'insérer un nouvel enregistrement dans la table mugs de la base de donnée que nous avons créé ensemble précédement.



Connaitre l'id du dernier enregistrement inséré :

Il peut être utile de connaitre l'ID du dernier enregistrement inséré, l'ID nous sert à retrouver un enregistrement en
particulier.

Pour le récuperer, on utilisera insert_id, exemple :

$dernierID = $conn->insert_id;


$dernierID va contenir l'id de l'enregistrement inséré dans la table mugs



Insérer plusieurs enregistrement en une seule requete :


Il est parfois utile d'insérer plusieurs enregistrement en utilisant une seule requete.

La méthode que nous pouvons utiliser avec MySQLi s'apelle multi_query()

Exemple :

$sql = "INSERT INTO mugs VALUES ('','Un mug noir');";
$sql. = "INSERT INTO mugs VALUES ('','Un mug jaune');";
$sql. = "INSERT INTO mugs VALUES ('','Un mug vert')";

if ($conn->multi_query($sql) === TRUE) {
    //Les enregistrements ont été ajouté

}


ATTENTION : lorsque vous executez plusieurs requetes sql d'un coup, celles ci doivent être séparées par des ;

Observez également $conn->multi_query, il est plaçé dans une condition, $conn->méthode renvoie true ou false en fonction
du résultat de l'opération, si vous n'avez pas d'erreurs dans vos instructions sql, true sera renvoyée.


Requetes préparées :

Les requetes préparées permettent d'améliorer les performances de vos requetes sql et ajoutent une sécurité supplémentaire
car nous indiquons le type de données qui vont être insérées dans notre table

Exemple ( reprise des inserts précédents ) :

$stmt = $conn->prepare("INSERT INTO mugs (description) VALUES (?)");
$stmt->bind_param("s", $description);

$description = "un mug noir";
$stmt->execute();

$description = "un mug jaune";
$stmt->execute();

$description = "un mug vert";
$stmt->execute();

$stmt->close();


Il est trés important de penser à fermer une requete préparée sinon les autres requetes que vous enverrez vont retourner
des erreurs.

Observez bien l'utilisation du ? dans VALUES, le ? est remplaçé par le parametre $description

Dans bind_param(), j'ai utilisé le parametre "s", cela permet d'indiquer au serveur sql que le type de donnée insérée doit
être une chaine de caracteres ( string en anglais )

"i" désigne un nombre entier par exemple

Par la suite j'utilise execute() pour executer la requete préparée, comme j'ai utilisé bind_param, php va récuperer la valeur
de $description à chaque execution.



Selectionner des données

Pour selectionner des données, il faut envoyer une requete SELECT sur notre table

Exemple :

$sql = "SELECT id,description from mugs";
$result = $conn->query($sql);

while($row = $result->fetch_assoc())
{
    echo"id =".$row['id']." mug =".$row['description']."<br><br>";
}


L'instruction important ici est $result->fetch_assoc() , elle permet de retourner sous forme d'un tableau associatif
tout les enregistrement correspondant à la requete SELECT

Je peux ensuite parcourir ce tableau comme n'importe quel tableau associatif, il est plus simple d'utiliser l'insruction
while ici ( car pas besoin de connaitre le nombre d'enregistrements retournés )

Pour connaitre le nombre d'enregistrements retournés , il est possible d'utiliser :

$result->num_rows;

Il peut être utile de connaitre cette valeur si on a beaucoup d'enregistrements dans la table ( il faudra alors prévoir
 un systemede pagination ) ou pour vérifier qu'il y a bien
au moins un enregistrement ( si il n'y en a pas, cela permet d'afficher un message à l'utilisateur par exemple )



Supprimer des données

Pour supprimer des données dans une table, il faut envoyer une requete DELETE

ATTENTION : Si vous souhaitez supprimer seulement un enregistrement, il faut absolument utiliser une clause WHERE, si
vous l'oubliez, tout le contenu de votre table sera effaçée.

Exemple :

$sql = "DELETE from mugs where id = 1";
$conn->query($sql);

Dans cet exemple je supprime l'enregistrement ayant pour ID 1 dans ma table mugs



Mettre à jour des données


Pour mettre à jour des données, il faut envoyer une requete UPDATE

ATTENTION : Comme pour DELETE, si vous ouliez la clause WHERE, tout les engistrements de la table seront mis à jour.

Exemple :

$sql = "UPDATE mugs set description = 'Un mug rose' where id = 1";
$conn->query($sql);

L'enregistrement ayant pour id 1 aura désormais comme description 'Un mug rose'



Limiter la selection des données


Il est souvent utile de limiter la portée des selections de données ( plus vous selectionnez de données, plus votre
requete prends du temps )

Pour faire cela, vous utiliserez l'instruction LIMIT

Exemple:

$sql = "SELECT id,description from mugs LIMIT 2";

On selectionne uniquement 2 enregistrements


$sql = "SELECT id,description from mugs LIMIT 15,10";

Ici , on selectionne uniquelebt 10 enregistrements à partir du 16 eme enregistrement ( premier parametre : 15 = Index de
départ , 10 = nombre d'enregistrements à selectionner à partir du premier argument )







