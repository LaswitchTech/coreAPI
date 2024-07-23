# Usage
## Initiate API
To use `API`, simply include the API.php file and create a new instance of the `API` class.

```php
<?php

// Import additionnal class into the global namespace
use LaswitchTech\coreAPI\API;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Initiate API
$API = new API();
```

### Properties
`API` provides the following properties:

#### core Modules
- [Configurator](https://github.com/LaswitchTech/coreConfigurator)
- [Logger](https://github.com/LaswitchTech/coreLogger)
- [Auth](https://github.com/LaswitchTech/coreAuth)
- [CSRF](https://github.com/LaswitchTech/coreCSRF)

### Skeleton
Let's start with the skeleton of your API project directory.

```sh
├── api.php
├── config
│   └── api.cfg
├── Controller
│   └── UserController.php
└── Model
    └── UserModel.php
```

* api.php: The api file is the entry-point of our application. It will initiate the controller being called in our application.
* config/api.cfg: The config file holds the configuration information of our API. Mainly, it will hold the database credentials. But you could use it to store other configurations.
* Controller/: This directory will contain all of your controllers.
* Controller/UserController.php: the User controller file which holds the necessary application code to entertain REST API calls. Mainly the methods that can be called.
* Model/: This directory will contain all of your models.
* Model/UserModel.php: the User model file which implements the necessary methods to interact with the users table in the MySQL database.

### Models
Model files implements the necessary methods to interact with a table in the MySQL database. These model files needs to extend the Database class in order to access the database.

See [coreBase](https://github.com/LaswitchTech/coreBase) for more information.

#### Naming convention
The name of your model file should start with a capital character and be followed by ```Model.php```.  If not, the bootstrap will not load it.
The class name in your Model files should match the name of the model file.

#### Example
```php

// Import BaseModel class into the global namespace
use LaswitchTech\coreBase\BaseModel;

class UserModel extends BaseModel {
    public function getUsers($limit) {
        return $this->select("SELECT * FROM users ORDER BY id ASC LIMIT ?", ["i", $limit]);
    }
}
```

### Controllers
Controller files holds the necessary application code to entertain REST API calls. Mainly the methods that can be called. These controller files needs to extend the BaseController class in order to access the basic methods.

See [coreBase](https://github.com/LaswitchTech/coreBase) for more information.

#### Naming convention
The name of your controller file should start with a capital character and be followed by ```Controller.php```.  If not, the bootstrap will not load it. The class name in your Controller files should match the name of the controller file.

Finally, callable methods need to end with ```APIAction```.

#### Example
```php

// Import BaseController class into the global namespace
use LaswitchTech\coreBase\BaseController;

class UserController extends BaseController {

    public function __construct($Auth){

        // Set the controller Authentication Policy
        $this->Public = true; // Set to false to require authentication

        // Set the controller Authorization Policy
        $this->Permission = false; // Set to true to require a permission for the namespace used. Ex: namespace>/user/list
        $this->Level = 1; // Set the permission level required

        // Call the parent constructor
        parent::__construct($Auth);
    }

    public function listAPIAction() {
        try {

            // Namespace: /user/list

            // Check the request method
            if($this->Method !== 'GET'){
                throw new Error('Invalid request method.');
            }

            // Initialize the user model
            $UserModel = new UserModel();

            // Configure default limit
            $Limit = 25;

            // Check if the limit is set
            if($this->getQueryStringParams('limit')){
                $Limit = intval($this->getQueryStringParams('limit'));
            }

            // Get the users
            $Users = $UserModel->getUsers($Limit);

            // Check if the users were found
            if(count($Users) <= 0){
                throw new Error('Users not found.');
            }

            // Send the output
            $this->output(
                $Users,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } catch (Error $e) {

            // Set the error
            $this->Error = $e->getMessage();

            // Log the error
            $this->Logger->error($e->getMessage());

            // Send the output
            $this->output(
                array('error' => $this->Error . ' - Something went wrong! Please contact support.'),
                array('Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error'),
            );
        }
    }
}
```
