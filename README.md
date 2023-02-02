# docker-mds

docker compose build --pull --no-cache    
docker compose up -d    

"docker ps" afin de voir le port pour RabbitMQ     

url : http://localhost    

Dans le fichier .env, modifier les lignes DATABASE_URL et MESSENGER_TRANSPORT_DSN avec vos informations    

php bin/console doctrine:database:create
