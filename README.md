Projet PWEB 2019, THELLIEZ Flavien, BIGOT Sebastien.
## Choix techniques:

Il n'y a pas tellement de choix techniques commplexes :
- Nous avons divisé les pages en différents onglets, ils différent selon le statut (admin ou utilisateur)
- Nous "identifions" l'utilisateur grâce à son nom de compte et non pas grâce à un id.(Sébastien) J'ai trouvé ça plus "naturel".
- Pour les tailles d'images, nous avons défini 4 états: taille originale, grand moyen et petit (resp. 100%, 75%, 50% et 25% de la taille originale), cela permet d'éviter les "écrasements" d'image si l'utilsateur choisit lui même sa résolution en pixels.
- Le "style" des tableaux n'est pas défini dans le fichier .css, mais dans les fonctions addHeader(), nous ne savons pas pourquoi, mais en le mettant dans le fichier .css, cela ne fonctionnait pas.

## Architecture:

- Différenciation de pages accessibles entre administrateur et utilisateur
- Schéma de l'architecture en .png

## Base de donnée

- Voir le .png