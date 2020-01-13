Projet PWEB 2019, BIGOT Sebastien, THELLIEZ Flavien.
## Description projet:

Réalisation d'une bibliothèque en ligne, dans laquelle nous pouvons créer un compte, voir les différentes oeuvres disponibles, les réserver, tandis qu'un administrateur dispose de diverses options pour gérer la bibliothèque: ajout/suppression de livres, désactivation temporaire d'un compte utilisateur et/ou suppression de celui-ci.

## Lancement du projet:

Lancer mainpage.php sous un serveur local (WAMP par exemple).

## Choix techniques:

Il n'y a pas tellement de choix techniques commplexes :
- Nous avons divisé les pages en différents onglets, ils différent selon le statut (admin ou utilisateur)
- Nous "identifions" l'utilisateur grâce à son nom de compte et non pas grâce à un id.(Sébastien) J'ai trouvé ça plus "naturel".
- Pour les tailles d'images, nous avons défini 4 états: taille originale, grand moyen et petit (resp. 100%, 75%, 50% et 25% de la taille originale), cela permet d'éviter les "écrasements" d'image si l'utilsateur choisit lui même sa résolution en pixels.
- Le "style" des tableaux n'est pas défini dans le fichier .css, mais dans les fonctions addHeader(), nous ne savons pas pourquoi, mais en le mettant dans le fichier .css, cela ne fonctionnait pas.
