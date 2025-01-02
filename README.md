## PAJ Assignment

Assignment submitted by Ronodip Basak


## Getting Started
First start by using the env file example as base env file, and generating application key
```
# Copy .env example file
cp .env.example .env

# Generate App Key for Laravel
php artisan key:generate
```

Once that's done, we need to set the following parameters on .env file
```
# In case of development environment (docker-compose.dev.yaml),
# we can leave DB_HOST to localhost, but it is important to set 
# DB_PORT to whatever mysql docker port is, in case of dev env, 
# 33060 is default, unless changed manually
DB_HOST=127.0.0.1
DB_PORT=33060
# These following values can be set to whatever we want
# HINT: you can generate a random password with : "openssl rand -hex 12"
DB_DATABASE=""
DB_USERNAME=""
DB_PASSWORD=""

```


Once it's done it's as simple as running docker compose up
```
docker compose up -d
```

This will expose application to [localhost on port 8080](http://localhost:8080)

## Setting up dev env
In case you want to set up dev env, after configuring .env file run the following:
```
docker compose -f docker-compose.dev.yaml up -d
php artisan serve
```

This will start laravel's default server on port 8000, and also expose port 8080 (from docker) phpmyadmin. In case you want to use some other SQL GUI, you can still connect to application database over port 33060

## Run API
Use the attached Postman Collection ("Main.postman_collection.json") to run manual tests