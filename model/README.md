# Model of SwimmPair web application
This folder is dedicated to model of SwimmPair web application https://github.com/KlosStepan/SwimmPair-Www Via. file start.php in folder above all classes are included, Managers are instantiated with live database connection and this file is included all around the web application. Therefore we view these functions as API functions of our web application because they are sole mean of communication between web and database.
## Utility Classes
There are several utility classes, usually static classes with functios or of enumeration character. They serve a purpose of static functions for Authentication, Sanitization or keeping priviledges and names of there appropriate ranks.
## Object Classes
Several classes representing object in this system. They have data and some serialization functions as well.
## Manager Classes
Manager class always moves around one class from Classes(as mentioned above) in order to provide API functions for this object.  
__
### Github repo of this model
This model resides in Github repo https://github.com/KlosStepan/SwimmPair-Www/tree/master/model and this documentation is generated against it.