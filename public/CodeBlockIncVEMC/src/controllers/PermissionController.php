<?php

namespace public\CodeBlockIncVEMC\src\controllers;

use public\CodeBlockIncVEMC\src\services\DBConnectionService;
use public\CodeBlockIncVEMC\src\services\LoginService;
use public\CodeBlockIncVEMC\src\services\PermissionService;
use Teacher\GivenCode\Exceptions\RequestException;
use Teacher\GivenCode\Exceptions\RuntimeException;

/**
 *
 */
class PermissionController {
    private PermissionService $permissionService;
    
    public function __construct() {
        //parent::__construct();
        $this->permissionService = new PermissionService();
    }
    
    /**
     * @return void
     * @throws RequestException
     */
    public function get() : void {
        // Login required to use this API functionality
        if (!LoginService::isUserLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        if (empty($_REQUEST["permId"])) {
            throw new RequestException("Bad request: required parameter [book] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["permId"])) {
            throw new RequestException("Bad request: parameter [permId] value [" . $_REQUEST["permId"] .
                                       "] is not numeric.", 400);
        }
        $int_id = (int) $_REQUEST["permId"];
        $instance = $this->permissionService->getById($int_id);
        $instance->loadUsers();
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($instance->toArray());
    }
    
    public function post() : void {
        // Login required to use this API functionality
        if (!LoginService::isUserLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        // I create the new book first then deal with the book-author associations.
        // I have to do this in that order in the create operation because the book does not already exists
        // in the database so the foreign key checks would fail if i tried creating associations first.
        if (empty($_REQUEST["permCode"])) {
            throw new RequestException("Bad request: required parameter [permCode] not found in the request.", 400);
        }
        if (empty($_REQUEST["permName"])) {
            throw new RequestException("Bad request: required parameter [permName] not found in the request.", 400);
        }
        if (empty($_REQUEST["permDesc"])) {
            throw new RequestException("Bad request: required parameter [permDesc] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["permId"])) {
            throw new RequestException("Bad request: ivalid parameter [permId] value: non-numeric value found [" .
                                       $_REQUEST["permId"] . "].", 400);
        }
        $id = (int) $_REQUEST["permId"];
        
        // create a transaction as i will be making many operations in the datatbase
        $connection = DBConnectionService::getConnection();
        $connection->beginTransaction();
        
        try {
            // create the permission first
            $instance = $this->permissionService->create($id, $_REQUEST["permCode"], $_REQUEST["permName"], $_REQUEST["permDesc"]);
            // then create the permission-user associations
            if (!empty($_REQUEST["users"]) || is_array($_REQUEST["users"])) {
                
                // create the selected associations
                foreach ($_REQUEST["users"] as $user_id => $is_checked) {
                    // only if checkbox value was checked.
                    // NOTE: unchecked checkbox pass the value 'false' as a string ao they still exist in the request
                    // and make the following == "true" check necessary.
                    if (strtolower($is_checked) == "true") {
                        $int_user_id = (int) $user_id;
                        $this->permissionService->associatePermitWithUser($instance->getId(), $int_user_id);
                    }
                }
            }
            
            // load the created associations
            $instance->loadUsers();
            // commit all DB operations
            $connection->commit();
            
            header("Content-Type: application/json;charset=UTF-8");
            echo json_encode($instance->toArray());
            
        } catch (\Exception $excep) {
            $connection->rollBack();
            throw $excep;
        }
    }
    
    /**
     * @return void
     * @throws RequestException
     */
    public function put() : void {
        
        // Login required to use this API functionality
        if (!LoginService::isUserLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        
        $raw_request_string = file_get_contents("php://input");
        parse_str($raw_request_string, $_REQUEST);
        
        
        if (empty($_REQUEST["permId"])) {
            throw new RequestException("Bad request: required parameter [permId] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["permId"])) {
            throw new RequestException("Bad request: ivalid parameter [permId] value: non-numeric value found [" .
                                       $_REQUEST["permId"] . "].", 400);
        }
        if (empty($_REQUEST["permName"])) {
            throw new RequestException("Bad request: required parameter [permName] not found in the request.", 400);
        }
        if (empty($_REQUEST["permCode"])) {
            throw new RequestException("Bad request: required parameter [permCode] not found in the request.", 400);
        }
        $int_perm_id = (int) $_REQUEST["permId"];
        $connection = DBConnectionService::getConnection();
        $connection->beginTransaction();
        
        try {
            
            // I handle dealing with the book-author associations first then do the book entity update.
            // I can do this in the update because the book already exists so the foreign key checks will not fail.
            
            // My approach is simply to delete all existing associations between the updating book and any authors
            // and then to re-create only those set from the checkbox inputs values from the request
            
            if (!empty($_REQUEST["users"]) || is_array($_REQUEST["users"])) {
                // delete all previous author associations for the book
                $this->usersService->deleteAllPermissionUserAssociationsForPermId($int_perm_id);
                
                // re-create the selected associations
                foreach ($_REQUEST["users"] as $user_id => $is_checked) {
                    // only if checkbox value was checked.
                    // NOTE: unchecked checkbox pass the value 'false' as a string ao they still exist in the request
                    // and make the following == "true" check necessary.
                    if (strtolower($is_checked) == "true") {
                        $int_user_id = (int) $user_id;
                        $this->permissionService->associatePermitsWithUser($int_perm_id, $int_user_id);
                    }
                }
            }
            
            // then update the main object
            $instance = $this->permissionService->update($int_perm_id, $_REQUEST["permCode"], $_REQUEST["permName"], $_REQUEST["permDesc"]);
            $instance->loadUsers();
            $connection->commit();
            
            header("Content-Type: application/json;charset=UTF-8");
            echo json_encode($instance->toArray());
            
        } catch (\Exception $excep) {
            $connection->rollBack();
            throw $excep;
        }
        
        
    }
    
    /**
     * @return void
     * @throws RequestException
     * @throws RuntimeException
     */
    public function delete() : void {
        
        // Login required to use this API functionality
        if (!LoginService::isUserLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        
        $raw_request_string = file_get_contents("php://input");
        parse_str($raw_request_string, $_REQUEST);
        
        
        if (empty($_REQUEST["permId"])) {
            throw new RequestException("Bad request: required parameter [permId] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["permId"])) {
            throw new RequestException("Bad request: ivalid parameter [permId] value: non-numeric value found [" .
                                       $_REQUEST["id"] . "].", 400);
        }
        
        $int_perm_id = (int) $_REQUEST["permId"];
        
        $connection = DBConnectionService::getConnection();
        $connection->beginTransaction();
        
        try {
            // I delete the book-author associations first then delete the book itself.
            // I have to do this in that order in the delete operation because the foreign key checks might block me
            // from deleting a book that still has existing associations (ON DELETE RESTRICT foreign key option).
            
            // delete all author associations for the book
            $this->permissionService->deleteAllPermittsUserAssociationsForPermId($int_perm_id);
            
            // delete the book itself
            $this->permissionService->delete($int_perm_id);
            
            // commit transaction operations
            $connection->commit();
            
            header("Content-Type: application/json;charset=UTF-8");
            // 204 NO CONTENT response code
            http_response_code(204);
            
        } catch (\Exception $excep) {
            $connection->rollBack();
            throw $excep;
        }
    }
}