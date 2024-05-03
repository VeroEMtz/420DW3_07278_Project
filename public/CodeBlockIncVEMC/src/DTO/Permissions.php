<?php
declare(strict_types=1);
/**
 * @author Veronica Mtz
 * @since  2024-05-02
 */

namespace public\CodeBlockIncVEMC\src\DTO;


use DateTime;
use public\CodeBlockIncVEMC\src\DAO\PermissionsDAO;
use Teacher\GivenCode\Exceptions\ValidationException;

class Permissions {
    public const TABLE_NAME = "permissions";
    public const PERMCODE_MAX_LENGTH = 4;
    public const PERNAME_MAX_LENGTH = 25;
    PUBLIC const PERMDESC_MAX_LEGTH = 255;
    
    private int $permId;
    private string $permCode;
    private string $permName;
    private string $permDesc;
    private ?DateTime $creationDate = null;
    private ?DateTime $updateDate = null;
    
    public function __construct(){}
    
    public static function fromValues(string $permCode, string $permName, string $permDesc) : Permissions{
        $instance = new Permissions();
        $instance->setPermCode($permCode);
        $instance->setPermName($permName);
        $instance->setPermDesc($permDesc);
        return $instance;
    }
    
    public static function fromDbArray(array $dbArray) : Permissions {
        self::validateDbArray($dbArray);
        $instance = new Permissions();
        $instance->setPermId((int) $dbArray["permId"]);
        $instance->setPermCode($dbArray["permCode"]);
        $instance->setPermName($dbArray["permName"]);
        $instance->setPermDesc($dbArray["permDesc"]);
        if (!empty($dbArray["creationDate"])) {
            $instance->setCreationDate(DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["setCreationDate"]));
        }
        if (!empty($dbArray["updateDate"])) {
            $instance->setUpdateDate(DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["updateDate"]));
        }
        return $instance;
    }
    
    public function getDatabaseTableName() : string {
        return self::TABLE_NAME;
    }
    
    //"GETTERS AND SETTERS"
    
    public function getPermId() : int {
        return $this->permId;
    }
    
    public function setPermId(int $permId) : void {
        if ($permId < 1) {
            throw new ValidationException("[permId] value must be a positive integer greater than 0.");
        }
        $this->permId = $permId;
    }
    
    public function getPermCode() : string {
        return $this->permCode;
    }
    
    public function setPermCode(string $permCode) : void {
        if (mb_strlen($permCode) > self::PERMCODE_MAX_LENGTH) {
            throw new ValidationException("[permCode] value must be a string no longer than " .
                                          self::PERMCODE_MAX_LENGTH . "characters; found length: [" .
                                          mb_strlen($permCode) . "].");
        }
    }
    
    public function getPermName() : string {
        return $this->permName;
    }
    
    
    public function setPermName(string $permName) : void {
        if (mb_strlen($permName) > self::PERNAME_MAX_LENGTH) {
            throw new ValidationException("[permName] value must be a string no longer than " .
                                          self::PERNAME_MAX_LENGTH . "characters; found length: [" .
                                          mb_strlen($permName) . "]");
        }
    }
    
    public function getPermDesc() : string {
        return $this->permDesc;
    }
    
    public function setPermDesc(string $permDesc) : void {
        if (mb_strlen($permDesc) > self::PERMDESC_MAX_LEGTH) {
            throw new ValidationException("[permDesc] value must be a string no longer than " .
                                          self::PERMDESC_MAX_LEGTH . "characters; found length: [" .
                                          mb_strlen($permDesc) . "].");
        }
    }
    
    public function getCreationDate() : DateTime {
        return $this->creationDate;
    }
    
    public function setCreationDate(DateTime $creationDate) : void {
        $this->creationDate = $creationDate;
    }
    
    public function getUpdateDate() : DateTime {
        return  $this->updateDate;
    }
    
    public function setUpdateDate(DateTime $updateDate) : void {
        $this->updateDate = $updateDate;
    }
    
    public function loadUsers() : void {
        $dao = new PermissionsDAO();
        $this->users = $dao->getUsrsbyPermits($this);
    }
    
    public function toArray() : array {
        $array = [
            "permId" => $this->getPermId(),
            "permCode" => $this->getPermCode(),
            "permName" => $this->getPermName(),
            "permDesc" => $this->getPermDesc(),
            "cretionDate" => $this->getCreationDate()?->format(HTML_DATETIME_FORMAT),
            "updateDate" => $this->getUpdateDate()?->format(HTML_DATETIME_FORMAT),
            "users" => []
        ];
        foreach ($this->users as $user) {
            $array["users"][$user->getUsrId()] = $user->toArray();
        }
        return $array;
    }
    
    public function validateForDbCreation() : void {
        if (!empty($this->permId)) {
            throw new ValidationException("Permissions is not valid for DB creation: ID value already set.");
        }
        if (empty($this->permCode)) {
            throw new ValidationException("Permissions is not valid for DB creation: permcode value not set.");
        }
        if (empty($this->permName)) {
            throw new ValidationException("Permissions is not valid for DB creation: permName value not set.");
        }
        if (empty($this->permDesc)) {
            throw new ValidationException("Permissions is not valid for DB creation: permDesc value not set.");
        }
        if (!is_null($this->creationDate)) {
            throw new ValidationException("Permissions is not valid for DB creation: creationDate value already set.");
        }
        if (!is_null($this->updateDate)) {
            throw new ValidationException("Permissions is not valid for DB creation: updateDate value already set.");
        }
    }
    
    public function validateForDbUpdate() : void {
        if (empty($this->permId)) {
            throw new ValidationException("Permissions is not valid for DB update: permId value not set.");
        }
        if (empty($this->permCode)) {
            throw new ValidationException("Permissions is not valid for DB update: permCode value not set.");
        }
        if (empty($this->permDesc)) {
            throw new ValidationException("Permissions is not valid for DB update: permDesc value not set.");
        }
        if (!is_null($this->creationDate)) {
            throw new ValidationException("Permissions is not valid for DB creation: creationDate value already set.");
        }
        if (!is_null($this->updateDate)) {
            throw new ValidationException("Permissions is not valid for DB creation: updateDate value already set.");
        }
    }
    
    public function validateForDbDelete() : void {
        // ID must be set
        if (empty($this->permId)) {
            throw new ValidationException("Permissions is not valid for DB update: permId value not set.");
        }
    }
    
    private static function validateDbArray(array $dbArray) : void {
        if (empty($dbArray["permId"])) {
            throw new ValidationException("Record array does not contain an [permId] field. Check column names.");
        }
        if (!is_numeric($dbArray["permId"])) {
            throw new ValidationException("Record array [permId] field is not numeric. Check column types.");
        }
        if (empty($dbArray["permCode"])) {
            throw new ValidationException("Record array does not contain an [permCode] field. Check column names.");
        }
        if (empty($dbArray["permDesc"])) {
            throw new ValidationException("Record array does not contain an [permDesc] field. Check column names.");
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
    
}