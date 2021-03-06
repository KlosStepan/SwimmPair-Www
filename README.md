# SwimmPair Web Application
SwimmPair is an application created for managing referees for swimming competitions in the Czech Republic. Application model describes administrative units such as regions, cups, users etc. which are administratively present and used to be handled manually - in Excel files or even worse, in hand. SwimmPair is here to solve this.
## Try it out!
```shell script
git clone /this
docker-compose up --detach 
```
## Web Application Stack & Deployment
Swimmpair is shipped via [docker](https://www.docker.com) cointainers & is run by [docker-compose](https://docs.docker.com/compose): tl;dr docker-compose.yaml
```yaml
services:
  php:
    image: thecodingmachine/php:7.4-v4-apache
  database:
    image: mysql:8.0
  adminer:
    image: adminer
```

## Web Application Structure
Web application is divided into
* public part - /www
* private part - /www/admin
* model - /www/model
* database w/ routines
* REST API for mobile app