# SwimmPair Web Application
SwimmPair is web application for managing swimming competitions in the Czech Republic. Application **model** describes administrative objects, such as regions, cups, clubs or users. The main goal was to automate administrative work formerly achived via Excel spreadsheets.

## Try it out!
```shell script
git clone https://github.com/KlosStepan/SwimmPair-Www
//import database and set up credentials
docker-compose up --detach 
```

## Web Application Structure Overview
The web application consists of these main parts:
* **public** part - www/index.php,
* **private** admin - www/admin/index.php,
* app **model** - www/model,
* mysql **database procedures** used by model.  

## Web Application Model Data Categorization
SwimmPair implements these structures to be moved around while administering the swimming competitions.  
Following objects are:
* **Page**/PagesManager - info pages,
* **Clubs**/ClubsManager - organization units of cups of users,
* **Cup**/CupsManager - swimming competition,
* **Region**/RegionsManager - geographical region,
* **User**/UsersManager - app users (admins, club managers, admins),
* **Position**/Positions - cup required work.


## Web Application Data Flow Architecture Overview
Application data flow is realized as follows: 
![App Schema](/misc/app-schema.jpg "app-schema")

Public and private part have **php form actions** and **ajax call endpoints** for achieving functionality via appropriate manager calls or payloads sent on them. The folders with these script actions (in public and private sections) are:
* PHPActionHandler,
* XMLHttpRequest.  

## Containerized Locel Development
SwimmPair is shipped for production in [docker image](https://www.docker.com). It's run locally by [docker-compose](https://docs.docker.com/compose), starting **SwimmPair**, **MySQL** and **Adminer** containers.  

Local development **docker-compose.yaml**:
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
## Production & Deployment Notes
Bundling website PHP files into Docker image with PHP runtime and Apache webserver is defined by Dockerfile.  

Dockerfile
```dockerfile
FROM thecodingmachine/php:7.4-v4-apache
COPY --chown=docker . /var/www/html
```
Dockerhub is in default public namespace - image can be tagged as [stepanklos/swimmpair](https://hub.docker.com/repository/docker/stepanklos/swimmpair) and is accessible publicly.  

Run to build in project folder
```zsh
docker build -t stepanklos/swimmpair .
```
Push into Dockerhub image repository
```zsh
docker push stepanklos/swimmpair
```

Bundled application doesn't come with database and adminer/phpmyadmin, so production is advised on cloud provider with database service or self-hosted database within persistent storage in the cluster.  


## Production in DOKS
- Application: Service - Deployment w/ **stepanklos/swimmpair**.
- MySQL:
  - Public Service - Service - Deployment - PVC - PC,
  - https://www.digitalocean.com/pricing/managed-databases .
- Database client: command line, Adminer deployment or Digital Ocean administration dashboard.  

Consider running 2 Node cluster and running replica on each one, reference **swimmpair-service** from Ingress routing. 

SwimmPair Service/Deployment for Kubernetes:
```yaml
apiVersion: v1
kind: Service
metadata:
  name: swimmpair-service
spec:
  type: ClusterIP
  ports:
  - port: 80
    targetPort: 80
  selector:
    app: swimmpair
---
apiVersion: apps/v1
kind: Deployment
metadata:
  name: swimmpair
spec:
  replicas: 2
  selector:
    matchLabels:
      app: swimmpair
  template:
    metadata:
      labels:
        app: swimmpair
    spec:
      containers:
      - name: swimmpair
        image: stepanklos/swimmpair:latest
        securityContext:
          allowPrivilegeEscalation: true
        ports:
        - containerPort: 80
        env:
        - name: MESSAGE
          value: Hello from swimmpair Deployment!
        - name: DATABASE_HOST
          value: 'mysql-service'
        - name: DATABASE_USER
          value: 'user'
        - name: DATABASE_PASS
          value: 'password'
        - name: DATABASE_NAME
          value: 'db'    
```