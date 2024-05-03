<?php
declare(strict_types=1);

namespace public\CodeBlockIncVEMC\src\controllers;

use Exception;
use public\CodeBlockIncVEMC\src\services\LoginService;
use Teacher\GivenCode\Abstracts\AbstractController;
use Teacher\GivenCode\Exceptions\RequestException;


/**
 *
 */
class LoginController { // extends AbstractController
    private LoginService $loginService;
    
    public function __construct() {
        //parent::__construct();
        $this->loginService = new LoginService();
    }
    
    /**
     * @return void
     * @throws RequestException
     */
    public function get() : void {
        // Voluntary exception throw: no GET operation supported for login system
        throw new RequestException("NOT IMPLEMENTED.", 501);
    }
    
    /**
     * @return void
     * @throws Exception
     */
    public function post() : void {
        
        try {
            if (empty($_REQUEST["usrname"])) {
                throw new RequestException("Missing required parameter [usrname] in request.", 400, [], 400);
            }
            if (empty($_REQUEST["password"])) {
                throw new RequestException("Missing required parameter [password] in request.", 400, [], 400);
            }
            echo "Entering Controllers post() method...";
            $usr = $_REQUEST["usrname"];
            $pass = $_REQUEST["password"];
            $this->loginService->doLogin($usr, $pass);
            if ($_SESSION["LOGGED_IN_USER"] != null) {
                header("location: home.php");
            } else {
                header("Location:\\420DW3_07278_Project\public\CodeBlockIncVEMC\pages\login.php");
            }
            // if the user came to the login page by being redirected from another page that required to be logged in
            // redirect to that originally requested page after login.
            $response = [
                "navigateTo" => WEB_ROOT_DIR
            ];
            if (!empty($_REQUEST["from"])) {
                $response["navigateTo"] = $_REQUEST["from"];
            }
            header("Content-Type: application/json;charset=UTF-8");
            echo json_encode($response);
            exit();
            
        } catch (Exception $excep) {
            throw new Exception("Failure to log user in.", $excep->getCode(), $excep);
        }
    }
    
    /**
     * @return void
     * @throws RequestException
     */
    public function put() : void {
        // Voluntary exception throw: no PUT operation supported for login system
        throw new RequestException("NOT IMPLEMENTED.", 501);
    }
    
    /**
     * @return void
     * @throws RequestException
     */
    public function delete() : void {
        // Voluntary exception throw: no DELETE operation supported for login system
        throw new RequestException("NOT IMPLEMENTED.", 501);
    }
    
    /**
     * @return void
     */
    public function logout() : void {
        try {
            $this->loginService->logout();
            header("Location: login.php");
        } catch (Exception $exception) {
            // Manejo de errores
            echo "Error: " . $exception->getMessage();
        }
    }
}