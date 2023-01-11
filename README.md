# SwimmPair Web Application
SwimmPair is web application for managing swimming competitions in the Czech Republic. Application `model` describes administrative objects, such as `regions`, `cups`, `clubs` or `users`. The main goal was to automate administrative work formerly achived via Excel spreadsheets.  

![App Schema](/misc/app-preview.png "app-schema")
Preview of SwimmPair - **public page** of `Cup` with **available** and **paired** `Users`.
## Try it out!
```shell script
git clone https://github.com/KlosStepan/SwimmPair-Www
//import database and set up credentials
docker-compose up --detach 
```
## Running instances  
[SwimmPair.cz](http://swimmpair.cz) - Production application SwimmPair live.  
[SwimmPair.STKL.cz](http://swimmpair.stkl.cz) - Development application SwimmPair (new features, bugfixes) before prod.  
[SwimmPair090.STKL.cz](http://swimmpair090.stkl.cz) - Legacy v0.90 pre refactor w/ old real data.


## Web Application Structure Overview
The web application consists of these main parts:
* **public** part - www,
* **private** admin - www/admin,
* app **model** - www/model,
* mysql **database procedures** used by model.  

## Web Application Model Data Categorization
SwimmPair implements these structures to be moved around while administering the swimming competitions.  
Following objects are:
* **Post**/PostsManager - informative posts on the homepage,
* **Page**/PagesManager - info pages,
* **Club**/ClubsManager - organization units of cups,
* **Cup**/CupsManager - swimming competitions,
* **Region**/RegionsManager - geographical regions,
* **User**/UsersManager - app users (admins, club managers, admins),
* **Position**/Positions - work required for cups.


## Web Application Data Flow Architecture Overview
Application flow is realized by accessing `application page` and calling `Managers` functionality, that wrapps database calls and returns results as PHP objects. 
![App Schema](/misc/app-schema.jpg "app-schema")

Public and private part have **PHP form-actions** and **Ajax endpoints** for achieving functionality via. appropriate manager calls or storing payloads sent to them via HTTP POST. The folders with these respective script actions (in public and private sections) are to be found in:
* PHPActionHandler,
* XMLHttpRequest.  

## Containerized Local Development
SwimmPair is shipped for production in [docker image](https://www.docker.com). It's run locally by [docker-compose](https://docs.docker.com/compose), starting **SwimmPair**, **MySQL** and **Adminer** containers.  
![docker compose rup](/misc/app-docker-compose-run.png "docker-compose-run")  

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
## Dockerized Production & Deployment Notes
Bundling PHP files into **Docker image** with base **PHP/Apache image** is defined by `Dockerfile`.  

```dockerfile
FROM thecodingmachine/php:7.4-v4-apache
COPY --chown=docker . /var/www/html
```
[Dockerhub](https://hub.docker.com) is default and public namespace for pulling all images - our image can be tagger as [stepanklos/swimmpair](https://hub.docker.com/repository/docker/stepanklos/swimmpair) and therefore be accessible publicly by this name.  

Build Docker image of swimmpair, tagged as **stepanklos/swimmpair**.
```zsh
docker build -t stepanklos/swimmpair .
```
Push **stepanklos/swimmpair** into Dockerhub image repository.
```zsh
docker push stepanklos/swimmpair
```

Bundled application doesn't come with **database** and **adminer/phpmyadmin**. We advise production on cloud provider with database service or self-hosted database storing in `Persistent Storage` accessed via `Persistent Volume Claim`.  


## Production in DOKS
Several production options in container cloud service providers are possible, be it ECS or EKS in Amazon AWS, some alternative in Microsoft Azure, or self-hosted Kubernetes/Rancher/OpenShift/VMware Tanzu. We have, however, chosen **DigitalOcean** - [DigitalOcean Kubernetes](https://www.digitalocean.com/products/kubernetes) because it suits us best.  

It is advised to run SwimmPair as follows:
- **Application**: Service + Deployment utilizing **stepanklos/swimmpair**.
- **MySQL DB**:
  - either Public Service - Service + Deployment + PVC -> PV,
  - or https://www.digitalocean.com/pricing/managed-databases.
- **Database Client**: command line / Adminer Deployment / administration dashboard of chosen cloud provider.  

Consider running `2 Node Cluster` running replica on each. Reference **swimmpair-service** `Service` from `Ingress` for cluster routing to access **swimmpair** Deployment with `2 Pods` (up-to-date `Replica Set`). 
![docker compose rup](/misc/app-kubernetes-doks-run.png "docker-compose-run")


### Kubernetes `Service` + `Deployment `
Configuration file **app-swimmpair.yaml**:
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
### Ingress
Ingress, add SwimmPair to config section `rules:` as following snippet: 
```yaml
  - host: "swimmpair.stkl.cz"
    http:
      paths:
      - pathType: Prefix
        path: "/"
        backend:
          service:
            name: swimmpair-service
            port:
              number: 80
```
### Run
Finally, apply `SwimmPair yaml config` and reapply `Ingress yaml config` as noted
```zsh
kubectl apply -f app-swimmpair.yaml
kubectl apply -f kubernetes-ingress-config.yaml
```
to achieve desired state of running application in the **Kubernetes Cluster**.