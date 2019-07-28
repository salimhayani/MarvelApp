Marvel APP
========================

**Prérequis**:  PHP 5.6 ou plus, Mysql 5, Composer, Git

**Installation**: 
1. Cloner le projet dans votre dossier www avec la commande suivante :
`git clone https://github.com/salimhayani/MarvelApp MarvelApp`
2. Importer le fichier MarvelApp_DB.sql qui se trouve dans la racine du projet dans votre serveur Mysql.
3. Se rendre dans la racine du projet `cd MarvelApp` et lancer la commande : `composer install`
4. La commande `composer install` va vous demander de fournir les paramètres de connexion à votre BD Mysql (laisser les paramètres mailer par defaut puisqu'on l'utilise pas), Vous pouvez les modifier après en modifiant le fichier de paramètres : MarvelApp/app/config/parameters.yml et mettre votre host, port et identifiants de connexion Mysql.

**Utilisation de l'application**:

* Connectez-cous sur `(ipdevotreservuer)/MarvelApp/web/ ` vous aurez une page de connexion (les identifiants pour vous connecter vous ont été transmis par mail),
* Après connexion vous aurez une page avec un header contenant un lien vers la homepage et un lien de déconnexion, dans le corps de la page vous aurez une liste des 20 personnages Marvel à partir du 100ème organisés en 4 par page, la navigation est en bas de page,
* Au clic sur le bouton '**En savoir plus**' d'un personnage vous serez redirigés vers une page contenant les détails demandés pour ce personnage,

**Personnalisation de la liste**:

* si vous souhaitez modifier le nombre de personnages dans une page il suffit de modifier la constante `CHARACTERS_PER_PAGE` se trouvant dans le fichier `MarvelApp\src\AppBundle\Controller\CharacterController.php`,
* si vous souhaitez modifier l'offset depuis lequel l'application charge les personnages il suffit de modifier la constante `FIRST_OFFSET` se trouvant dans le fichier `MarvelApp\src\AppBundle\Controller\CharacterController.php`,
* si vous souhaitez modifier le nombre de personnages que l'application récupère depuis l'API Marvel il suffit de modifier la constante `NUMBER_OF_CHARACTERS_TO_GET` se trouvant dans le fichier `MarvelApp\src\AppBundle\Controller\CharacterController.php`,


