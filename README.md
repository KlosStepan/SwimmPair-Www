# SwimmPair Web Application
SwimmPair is an application created for managing referees for swimming competitions in the Czech Republic. Application model describes administrative units such as regions, cups, users etc. which are administratively present and used to be handled manually - in Excel files or even worse, in hand. SwimmPair is here to solve this.
## Try it out!
```shell script
git clone /this
docker-compose up --detach 
```
## Web Application Stack & Deployment
Swimmpair is shipped via [docker](https://www.docker.com) cointainers & run by [docker-compose](https://docs.docker.com/compose)
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
      MYSQL_ROOT_PASSWORD: "passwd"
    volumes:
      - mysql-data:/var/lib/mysql
  php:
    image: thecodingmachine/php:7.4-v4-apache
    ports:
      - "80:80"
    environment:
      DATABASE_HOST: 'database'
      DATABASE_USER: 'root'
      DATABASE_PASS: 'passwd'
      DATABASE_NAME: 'plavani'
    volumes:
      - ./:/var/www/html
volumes:
  mysql-data:
```

## Web Application Structure
Web application is divided into
* public part
* private part
* database w/ routines
* REST API for mobile app