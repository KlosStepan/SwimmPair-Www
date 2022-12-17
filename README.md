# SwimmPair Web Application
SwimmPair is web application for managing swimming competitions in the Czech Republic. Application **model** describes administrative objects, such as regions, cups, clubs or users. The main goal was to automate administrative work formerly achived via Excel spreadsheets.

## Try it out!
```shell script
git clone https://github.com/KlosStepan/SwimmPair-Www
//import database and set up credentials
docker-compose up --detach 
```

## Web Application Structure
The web application consists of these main parts:
* **public** part - www,
* **private** admin - www/admin,
* app **model** - www/model,
* mysql **database procedures** used by model.  

Public and private parts have php form actions and ajax calls for achieving functionality. 

## Web Application Development
SwimmPair is shipped in [docker image](https://www.docker.com). It's run locally by [docker-compose](https://docs.docker.com/compose), starting **SwimmPair**, **MySQL** and **Adminer** containers.
```yaml
version: "3.9"
services:
  adminer:
    image: adminer
    ports:
      - 8080:8080
  database:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: "mysql_root_passwd"
    volumes:
      - mysql-data:/var/lib/mysql
  php:
    image: thecodingmachine/php:7.4-v4-apache
    ports:
      - "80:80"
    environment:
      DATABASE_HOST: 'database'
      DATABASE_USER: 'user'
      DATABASE_PASS: 'password'
      DATABASE_NAME: 'db'
    volumes:
      - ./:/var/www/html
volumes:
  mysql-data:
```
## Web Application Production
Bundling website PHP files into Docker image with PHP runtime and Apache webserver is defined by Dockerfile.
```dockerfile
FROM thecodingmachine/php:7.4-v4-apache
COPY --chown=docker . /var/www/html
```
Dockerhub is in default public namespace - image can be tagged as [stepanklos/swimmpair](https://hub.docker.com/repository/docker/stepanklos/swimmpair) and is accessible publicly.  

Bundled application doesn't come with database and adminer/phpmyadmin, so production is advised on cloud provider with database service or self-hosted database within persistent storage in the cluster.  


## Production in DOKS
- Application: Service - Deployment w/ **stepanklos/swimmpair**
- MySQL:
  - Public Service - Service - Deployment - PVC - PC
  - https://www.digitalocean.com/pricing/managed-databases
- Database client: command line, Adminer deployment, Digital Ocean administration