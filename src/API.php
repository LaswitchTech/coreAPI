<?php

// Declaring namespace
namespace LaswitchTech\coreAPI;

// Import additionnal class into the global namespace
use LaswitchTech\coreConfigurator\Configurator;
use LaswitchTech\coreLogger\Logger;
use LaswitchTech\coreAuth\Auth;
use Exception;

class API {

	// core Modules
	private $Configurator;
  	private $Logger;
    private $Auth;

    // API Objects
    private $URI;

    /**
     * API constructor.
     */
    public function __construct() {

        // Initialize Configurator if class exists
        if(!class_exists('LaswitchTech\coreConfigurator\Configurator')){
            $this->sendOutput('Configurator class not found', array('HTTP/1.1 500 Internal Server Error'));
        }
        $this->Configurator = new Configurator('api','requirements');

        // Initiate Logger if class exists
        if(!class_exists('LaswitchTech\coreLogger\Logger')){
            $this->sendOutput('Logger class not found', array('HTTP/1.1 500 Internal Server Error'));
        }
        $this->Logger = new Logger('api');

        // Initiate Auth if class exists
        if(class_exists('LaswitchTech\coreAuth\Auth')){
            $this->Auth = new Auth();
        }

        // Include all model files
        if(is_dir($this->Configurator->root() . "/Model")){
            foreach(scandir($this->Configurator->root() . "/Model/") as $model){
                if(str_contains($model, 'Model.php')){
                    require_once $this->Configurator->root() . "/Model/" . $model;
                }
            }
        }

        // Parse URL
        $this->URI = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->URI = explode( '/', $this->URI );
        if(isset($this->URI[2],$this->URI[3])){

            // Identify Controller
            $strControllerName = ucfirst($this->URI[2]) . "Controller";
            $strMethodName = $this->URI[3] . 'APIAction';
            if(is_file($this->Configurator->root() . "/Controller/" . $strControllerName . ".php")){

                // Load Controller
                require $this->Configurator->root() . "/Controller/" . $strControllerName . ".php";

                // Create Controller
                $objFeedController = new $strControllerName($this->Auth);

                // Call Method
                $objFeedController->{$strMethodName}();
            } else {

                // Could not find Controller
                $this->sendOutput('Could not find Controller', array('HTTP/1.1 404 Not Found'));
            }
        } else {

            // Could not identify the Controller and/or Method
            $this->sendOutput('Could not identify the Controller and/or Action', array('HTTP/1.1 422 Unprocessable Entity'));
        }
    }

    /**
     * Send the output
     *
     * @param $data
     * @param array $httpHeaders
     * @return void
     */
    private function sendOutput($data, $httpHeaders=array()) {

        // Remove the default Set-Cookie header
        header_remove('Set-Cookie');

        // Add the custom headers
        if (is_array($httpHeaders) && count($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }

        // Check if the data is an array or object
        if(is_array($data) || is_object($data)){

            // Convert the data to JSON
            $data = json_encode($data,JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        }

        // Send the output
        echo $data;

        // Exit the script
        exit;
    }

    /**
     * Check if the required modules are installed
     *
     * @return bool
     */
    protected function isInstalled(){

        // Retrieve the list of required modules
        $modules = $this->Configurator->get('requirements','modules');

        // Check if the required modules are installed
        foreach($modules as $module){

            // Check if the class exists
            if (!class_exists($module)) {
                return false;
            }

            // Initialize the class
            $class = new $module();

            // Check if the method exists
            if(method_exists($class, isInstalled)){
                if(!$class->isInstalled()){
                    return false;
                }
            }
        }

        // Return true
        return true;
    }
}
