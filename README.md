# docker-mds

```
docker compose build --pull --no-cache    
docker compose up -d    
```

"docker ps" afin de voir le port pour RabbitMQ   
Il faut se rendre sur RabbitMQ et créer une Queue, le nom de la queue devra être renseigné dans le fichier .env à l'emplacement "/QUEUE"  

default url : http://localhost    

Dans le fichier .env, modifier les lignes DATABASE_URL et MESSENGER_TRANSPORT_DSN avec vos informations    

Création de la BDD : 
```
php bin/console doctrine:database:create
```

## POST

GET ALL (GET): /status/  
GET By ID (GET): /status/{id}  
CREATE (POST): /status/create  (param : "status")
EDIT (PUT): /status/update/{id}/.  (param : "status")
DELETE (POST):  /status/{id}  


Lors de la création d'un status, un message est envoyé à RabbitMQ, puis le worker change son contenu en "Status mis à jour".    
(Voir messenger.yaml, StatusController & StatusMessageHandler)