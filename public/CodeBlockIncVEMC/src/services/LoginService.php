<?php
declare(strict_types=1);

namespace public\CodeBlockIncVEMC\src\services;

//use Debug;
//use Exception;
use Debug;
use Exception;
use public\CodeBlockIncVEMC\src\DAO\UsersDAO;
use public\CodeBlockIncVEMC\src\DTO\Users;
use Teacher\GivenCode\Abstracts\IService;

class LoginService {
    
    private UsersService $usersService;
    
    public function __construct() {
        $this->usersService = new UsersService();
    }
    
    public static function requireVero() : bool {
        $return_value = false;
        if (!empty($_SESSION["LOGGED_IN_USER"]) && ($_SESSION["LOGGED_IN_USER"] instanceof Users)) {
            $requiredUser = (new UsersService())->getUserById(1);
            $user_object = $_SESSION["LOGGED_IN_USER"];
            if ($user_object->getUsrId() === $requiredUser->getUsrId()) {
                $return_value = true;
            }
        }
        return $return_value;
    }
    
    /**
     * @return bool
     */
    public static function isUserLoggedIn() : bool {
        $return_val = false;
        if (!empty($_SESSION["LOGGED_IN_USER"]) && ($_SESSION["LOGGED_IN_USER"] instanceof Users)) {
            $return_val = true;
        }
        Debug::log(("Is logged in user check result: [" . $return_val)
                       ? "true"
                       : ("false" . "]" .
                ($return_val ? (" id# [" . $_SESSION["LOGGED_IN_USER"]->getId() . "].") : ".")));
        return $return_val;
    }
    
    /**
     * @return void
     */
    public static function redirectToLogin() : void {
        header("Location: " . WEB_ROOT_DIR . "pages/login?from=" . $_SERVER["REQUEST_URI"]);
        http_response_code(303);
        exit();
    }
    
    /**
     * @return void
     */
    public static function requireLoggedInUser() : void {
        if (!self::isUserLoggedIn()) {
            // not logged in, do a redirection to the login page.
            // Note that I am adding a 'from' URL parameter that will be used to send the user to the right page after login
            self::redirectToLogin();
        }
    }
    
    /**
     * @return void
     */
    public function doLogout() : void {
        $_SESSION["LOGGED_IN_USER"] = null;
        Debug::debugToHtmlTable($_SESSION);
    }
    
    /**
     * @param int $usrId
     * @return void
     */
    public function doLogin(int $usrId) : void {
        $user = $this->usersService->getUserById($usrId);
        if (is_null($user)) {
            throw new Exception("User id# [$usrId] not found.", 404);
        }
        $_SESSION["LOGGED_IN_USER"] = $user;
    }
}
?>