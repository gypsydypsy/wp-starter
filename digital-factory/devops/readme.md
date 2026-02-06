UPDATE ANYTHING :

```
git subtree pull --prefix digital-factory/devops https://gitlab.havasdigitalfactory.net/exploitation/devops.git docker-worpdress-classique --squash
```

```
git subtree pull --prefix digital-factory/devops https://gitlab.havasdigitalfactory.net/exploitation/devops.git docker-worpdress-react --squash
```

Wordpress React : 

!!!!! Se mettre à la racine du projet !!!!!

```
docker-compose -f digital-factory/devops/docker-compose.yml --env-file=.env-docker up --build --remove-orphans
```

Local URI : https://localhost-$CLIENT-$PROJET.mondocker.fr/
   BO URI : https://localhost-$CLIENT-$PROJET.mondocker.fr/app/hdf-manager


Wordpress Classique : 

!!!!! Se mettre à la racine du projet !!!!!

```
docker-compose -f digital-factory/devops/docker-compose.yml --env-file=.env-docker up --build --remove-orphans
```

Local URI : https://localhost-$CLIENT-$PROJET.mondocker.fr/
   BO URI : https://localhost-$CLIENT-$PROJET.mondocker.fr/hdf-manager


Dans les deux cas au préalable :

Copier le .env-sample à la racine du projet et le renommer en .env-docker

Remplacer : 

- Les versions de PHP, NodeJS en conséquences
- CUSTOMER=''
- PROJECT=''
- WORDPRESS_CONTENT_PATH=''
- DOCUMENTROOT='htdocs' ou DOCUMENTROOT='htdocs/docroot'
- MYSQL_DB_PREFIX='wordpressprefix_'


Penser à bien exporter la base de donnée depuis la dev ou la prod avant de lancer le docker-compose :


MigrateDB Pro react :

   //intranet-spa-refonte.havasdigitalfactory.net => //localhost-spa-refonte.mondocker.fr

   /var/www/spa/refonte/capistrano/releases/20220211112758/htdocs/app => /usr/local/apache2/htdocs/app

   /var/www/spa/refonte/htdocs/app => /usr/local/apache2/htdocs/app

MigrateDB Pro classique :

   //intranet-laventure-refonte.havasdigitalfactory.net => //localhost-peugeot-laventure.mondocker.fr

   /var/www/laventure/refonte/capistrano/releases/20220209112833/htdocs => /usr/local/apache2/htdocs

   /var/www/laventure/refonte/htdocs => /usr/local/apache2/htdocs
