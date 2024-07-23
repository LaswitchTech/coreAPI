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
