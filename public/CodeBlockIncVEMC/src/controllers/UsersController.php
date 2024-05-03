<?php

namespace public\CodeBlockIncVEMC\src\controllers;

use public\CodeBlockIncVEMC\src\services\LoginService;
use public\CodeBlockIncVEMC\src\services\UsersService;
use Teacher\GivenCode\Exceptions\RequestException;

class UsersController {
    private UsersService $usersService;
    
    public function __construct() {
        //parent::__construct();
        $this->usersService = new UsersService();
    }
    
    public function getAll() : void {
        
        if (!LoginService::isUserLoggedIn()) {
            // No está iniciado sesión: responder con 401 NO AUTORIZADO
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        
        $users = $this->usersService->getAllUsers();
        
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($users);
    }
    
    public function getById() : void {
        ob_start();
        // Login required to use this API functionality
        if (!LoginService::isUserLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        
        if (empty($_REQUEST["userId"])) {
            throw new RequestException("Bad request: required parameter [userId] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["userId"])) {
            throw new RequestException("Bad request: parameter [userId] value [" . $_REQUEST["userId"] .
                                       "] is not numeric.", 400);
        }
        $int_id = (int) $_REQUEST["userId"];
        $instance = $this->usersService->getUserById($int_id);
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($instance->toArray());
        ob_end_flush();
    }
    
    public function post() : void {
        ob_start();
        
        // Login required to use this API functionality
        if (!LoginService::isUserLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        
        if (empty($_REQUEST["usrName"])) {
            throw new RequestException("Bad request: required parameter [usrName] not found in the request.", 400);
        }
        if (empty($_REQUEST["password"])) {
            throw new RequestException("Bad request: required parameter [password] not found in the request.", 400);
        }
        
        // NOTE: no need for validation of the string lengths here, as that is done by the setter methods of the
        // ExampleDTO class used when creating an ExampleDTO instance in the creation method of ExampleService.
        
        $instance = $this->usersService->createUser($_REQUEST["usrName"], $_REQUEST["password"]);
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($instance->toArray());
        ob_end_flush();
    }
    
    public function put() : void {
        ob_start();
        // Login required to use this API functionality
        if (!LoginService::isUserLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        
        $request_contents = file_get_contents("php://input");
        parse_str($request_contents, $_REQUEST);
        
        if (empty($_REQUEST["usrId"])) {
            throw new RequestException("Bad request: required parameter [usrId] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["usrId"])) {
            throw new RequestException("Bad request: ivalid parameter [usrId] value: non-numeric value found [" .
                                       $_REQUEST["usrId"] . "].", 400);
        }
        if (empty($_REQUEST["usrName"])) {
            throw new RequestException("Bad request: required parameter [usrName] not found in the request.", 400);
        }
        if (empty($_REQUEST["password"])) {
            throw new RequestException("Bad request: required parameter [password] not found in the request.", 400);
        }
        
        // NOTE: no need for validation of the string lengths here, as that is done by the setter methods of the
        // ExampleDTO class used when creating an ExampleDTO instance in the creation method of ExampleService.
        
        $int_id = (int) $_REQUEST["usrId"];
        
        $instance = $this->usersService->updateUsers($int_id, $_REQUEST["usrName"], $_REQUEST["password"]);
        $instance->loadPermits();
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($instance->toArray());
        ob_end_flush();
    }
    
    public function delete() : void {
        ob_start();
        // Login required to use this API functionality
        if (!LoginService::isUserLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        
        $request_contents = file_get_contents("php://input");
        parse_str($request_contents, $_REQUEST);
        
        if (empty($_REQUEST["usrId"])) {
            throw new RequestException("Bad request: required parameter [usrId] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["usrId"])) {
            throw new RequestException("Bad request: parameter [usrId] value [" . $_REQUEST["usrId"] .
                                       "] is not numeric.", 400);
        }
        $int_id = (int) $_REQUEST["usrId"];
        $this->usersService->deleteUsersById($int_id);
        header("Content-Type: application/json;charset=UTF-8");
        http_response_code(204);
    }
    
}