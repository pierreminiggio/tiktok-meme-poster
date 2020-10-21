# tiktok-poster

Prérequis :
- Un compte TikTok lié à un compte Facebook
- NodeJS installé et l'exécutable node dans les variables d'environement
- PHP 7.4 installé et l'exécutable php dans les variables d'environement
- Composer installé  et l'exécutable php dans les variables d'environement
- <a href="https://github.com/puppeteer/puppeteer">Ceux de Puppeteer</a>
- Sony Vegas Pro Installé, <a href="https://www.youtube.com/watch?v=-sbZ7worIJw">tuto pour installer Vegas ici</a>

Installation

Copier le projet :
```
git clone https://github.com/pierreminiggio/tiktok-meme-poster
```

Se déplacer dans le projet :
```
cd tiktok-meme-poster
```

Installer les dépendances :
```
npm install
cd server
composer install
cd ../
```

Initialiser les fichiers de stockage temporaire :
```
echo '[]' > queue.json
echo '[]' > posted.json
```

Configurer la connexion à Vegas Pro :
```
cp config_example.json config.json
```
Modifier le fichier le config.json pour mettre le lien vers l'exe de Vegas Pro.

Configurer la connexion à son compte TiKTok (via son compte Facebook) :
```
cp example_ids.json ids.json
```
Modifier le fichier le ids.json pour mettre les identifiant Facebook.


Lancer le logiciel de création de vidéo à partir d'une image :
```
npm start
```
Une page web va s'ouvrir, il suffit de modifier la légende et de coller (Ctrl + V) une image sur la page web pour que la vidéo se crée.

Lancer la publication automatique (une par heure) des vidéos crées :
```
npm run queue-poster
```
