<?php

/**
 * 420DW3_07278_Project Users.php
 *
 * @author  Veronica Elisa Martinez Contreras
 * @since   2024-04-28
 * (c) Copyright 2024 Veronica Martinez
 */

namespace public\CodeBlockIncVEMC\src\DTO;


use DateTime;
use Exception;
use public\CodeBlockIncVEMC\src\DAO\UsersDAO;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;


/**
 * @author Veronica Martinez
 * @since 2024-05-01
 */

class Users{
    public const TABLE_NAME = "users";
    
    private const NAME_MAX_LENGTH = 30;
    private const EMAIL_MAX_LENGTH = 80;
    private const PASSWORD_MAX_LENGTH = 255;
    
    //properties
    private int $usrId;
    private string $usrName;
    private string $password;
    private string $email;
    private ?DateTime $creationDate = null;
    private ?DateTime $updateDate = null;
    
    private array $permissions = [];
    
    public function __construct() {}
    
    /**
     * @param string $usrName
     * @param string $password
     * @param string $email
     * @return Users
     * @throws ValidationException
     *
     * @author Veronica Martinez
     * @since  2024-04-28
     */
    public static function fromValues(string $usrName, string $password, string $email) : Users {
        $instance = new Users();
        $instance->setUsrName($usrName);
        $instance->setPassword($password);
        $instance->setEmail($email);
        return $instance;
    }
    
    /**
     * @param array $dbArray
     * @return Users
     * @throws ValidationException
     *
     * @author  Veronica Martinez
     * @since   2024-04/28
     */
    public static function fromDbArray(array $dbArray) : Users {
        self::validateDBArray($dbArray);
        $instance = new Users();
        $instance->setUsrId((int) $dbArray["usrId"]);
        $instance->setUsrName($dbArray["usrName"]);
        $instance->setPassword($dbArray["password"]);
        $instance->setEmail($dbArray["email"]);
        $instance->setCreationDate(
            DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["createdDate"])
        );
        $instance->setUpdateDate(
            DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["updateDate"])
        );
        return $instance;
    }
    
    private static function validateDbArray(array $dbArray) : void {
        if (empty($dbArray["usrId"])) {
            throw new ValidationException("Record array does not contain an [usrId] field. Check column names.");
        }
        if (!is_numeric($dbArray["usrId"])) {
            throw new ValidationException("Record array [id] field is not numeric. Check column types.");
        }
        if (empty($dbArray["usrName"])) {
            throw new ValidationException("Record array does not contain an [usrName] field. Check column names.");
        }
        if (empty($dbArray["password"])) {
            throw new ValidationException("Record array does not contain a [password] field. Check column names.");
        }
        if (empty($dbArray["creationDate"])) {
            throw new ValidationException("Record array does not contain an [creationDate] field. Check column names.");
        }
        if (DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["creationDate"]) === false) {
            throw new ValidationException("Failed to parse [creationDate] field as DateTime. Check column types.");
        }
        if (!empty($dbArray["updateDate"]) &&
            (DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["updateDate"]) === false)
        ) {
            throw new ValidationException("Failed to parse [updateDate] field as DateTime. Check column types.");
        }
    }
    
    public function validateForDbCreation(bool $optThrowExceptions = true) : bool {
        // ID must not be set
        if (!empty($this->usrId)) {
            if ($optThrowExceptions) {
                throw new ValidationException("User is not valid for DB creation: ID value already set.");
            }
            return false;
        }
        // firstName is required
        if (empty($this->usrName)) {
            if ($optThrowExceptions) {
                throw new ValidationException("User is not valid for DB creation: user name value not set.");
            }
            return false;
        }
        // lastName is required
        if (empty($this->email)) {
            if ($optThrowExceptions) {
                throw new ValidationException("User is not valid for DB creation: email value not set.");
            }
            return false;
        }
        if (!is_null($this->creationDate)) {
            if ($optThrowExceptions) {
                throw new ValidationException("User is not valid for DB creation: creationDate value already set.");
            }
            return false;
        }
        if (!is_null($this->updateDate)) {
            if ($optThrowExceptions) {
                throw new ValidationException("User is not valid for DB creation: updateDate value already set.");
            }
            return false;
        }
        return true;
    }
    
    public function validateForDbUpdate(bool $optThrowExceptions = true) : bool {
        // ID is required
        if (empty($this->usrId)) {
            if ($optThrowExceptions) {
                throw new ValidationException("User is not valid for DB update: ID value is not set.");
            }
            return false;
        }
        // firstName is required
        if (empty($this->password)) {
            if ($optThrowExceptions) {
                throw new ValidationException("User is not valid for DB update: password value not set.");
            }
            return false;
        }
        // lastName is required
        if (empty($this->email)) {
            if ($optThrowExceptions) {
                throw new ValidationException("User is not valid for DB update: email value not set.");
            }
            return false;
        }
        return true;
    }
    
    public function validateForDbDelete(bool $optThrowExceptions = true) : bool {
        // ID is required
        if (empty($this->usrId)) {
            if ($optThrowExceptions) {
                throw new ValidationException("User is not valid for DB creation: ID value is not set.");
            }
            return false;
        }
        return true;
    }
    
    // "GETTERS AND SETTERS"
    
    /**
     * @return int
     */
    public function getUsrId() : int {
        return $this->usrId;
    }
    
    /**
     * @param int $usrId
     * @return void
     */
    public function setUsrId(int $usrId) : void {
        if ($usrId <= 0) {
            throw new ValidationException("[usrid] value must be a positive integer grater than 0");
        }
        $this->usrId = $usrId;
    }
    
    /**
     * @return string
     */
    public function getUsrName() : string {
        return $this->usrName;
    }
    
    /**
     * @param string $usrName
     * @return void
     */
    public function setUsrName(string $usrName) : void {
        if (mb_strlen($usrName) > self::NAME_MAX_LENGTH){
            throw new ValidationException("[usrName] value must be a string no longer than" . self::NAME_MAX_LENGTH .
                                          "characters; found length: [" . mb_strlen($usrName) . "].");
        }
        $this->usrName = $usrName;
    }
    
    /**
     * @return string
     */
    public function getPassword() : string {
        return $this->password;
    }
    
    /**
     * @param string $password
     * @return void
     */
    public function setPassword(string $password) : void {
        if (is_string($password) && (mb_strlen($password) > self::PASSWORD_MAX_LENGTH)) {
            throw new ValidationException("[paswword] value must be a string no longer than" .
                                          self::PASSWORD_MAX_LENGTH . "characters; found length: [" .
                                          mb_strlen($password) . "].");
        }
        $this->password = $password;
    }
    
    /**
     * @return string
     */
    public function getEmail() : string {
        return $this->email;
    }
    
    /**
     * @param string $email
     * @return void
     */
    public function setEmail(string $email) : void {
        if (sterlen($email) > self::EMAIL_MAX_LENGTH) {
            throw new ValidationException("[email] value must be a string no longer than" .
                                          self::EMAIL_MAX_LENGTH . "characters; found length: [" .
                                          mb_strlen($email) . "].");
        }
        $this->email = $email;
    }
    
    /**
     * @return DateTime|null
     */
    public function getCreationDate() : ?DateTime {
        return $this->creationDate;
    }
    
    /**
     * @param DateTime|null $creationDate
     * @return void
     */
    public function setCreationDate(?DateTime $creationDate) : void {
        $this->creationDate = $creationDate;
    }
    
    /**
     * @return DateTime|null
     */
    public function getUpdateDate() : ?DateTime {
        return $this->updateDate;
    }
    
    /**
     * @param DateTime|null $updateDate
     * @return void
     */
    public function setUpdateDate(?DateTime $updateDate) : void {
        $this->updateDate = $updateDate;
    }
    
    public function getPermits(bool $forceReload = false) : array {
        try {
            if (empty($this->permissions) || $forceReload) {
                $this->loadPermits();
            }
        } catch (Exception $excep) {
            throw new RuntimeException("Failed to load permits entity records for user id# [$this->usrId].", $excep->getCode(), $excep);
        }
        return $this->permissions;
    }
    
    // </editor-fold>
    
    
    public function loadPermits() : void {
        $dao = new Users();
        $this->permissions = $dao->getPermsByUSer($this);
    }
    
    public function toArray() : array {
        $array = [
            "usrId" => $this->getUsrId(),
            "usrName" => $this->getUsrName(),
            "password" => $this->getPassword(),
            "creationDate" => $this->getCreationDate()?->format(HTML_DATETIME_FORMAT),
            "updateDate" => $this->getUpdateDate()?->format(HTML_DATETIME_FORMAT),
            "permissions" => []
        ];
        // Note: i'm not using getBooks() here in order not to trigger the loading of the books.
        // Include them in the array only if loaded previously.
        // otherwise infinite loop author loads books loads authors loads books loads authors...
        foreach ($this->permissions as $perms) {
            $array["permissions"][$perms->getId()] = $perms->toArray();
        }
        return $array;
    }
    
    public function getDatabaseTableName() : string {
        return self::TABLE_NAME;
    }
    
}