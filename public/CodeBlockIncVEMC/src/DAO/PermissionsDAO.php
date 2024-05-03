<?php
declare(strict_types=1);

namespace public\CodeBlockIncVEMC\src\DAO;

use PDO;
use public\CodeBlockIncVEMC\src\DTO\Permissions;
use public\CodeBlockIncVEMC\src\DTO\Users;
use public\CodeBlockIncVEMC\src\services\DBConnectionService;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;

class PermissionsDAO {
    public function __construct() {}
    
    /**
     * @return array
     * @throws RuntimeException
     */
    public function getAll() : array {
        $query = "SELECT * FROM " . Permissions::TABLE_NAME . ";";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->execute();
        $records_array = $statement->fetchAll(PDO::FETCH_ASSOC);
        $users = [];
        foreach ($records_array as $record) {
            $users[] = Permissions::fromDbArray($record);
        }
        return $users;
    }
    
    /**
     * @param int $permId
     * @return Permissions|null
     * @throws RuntimeException
     */
    public function getById(int $permId) : ?Permissions {
        $query = "SELECT * FROM " . Permissions::TABLE_NAME . " WHERE `permId` = :id ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":id", $permId, PDO::PARAM_INT);
        $statement->execute();
        $record_array = $statement->fetch(PDO::FETCH_ASSOC);
        return Permissions::fromDbArray($record_array);
    }
    
    /**
     * @throws ValidationException
     * @throws RuntimeException
     */
    public function insert(Permissions $perm) : Permissions {
        $perm->validateForDbCreation();
        $query =
            "INSERT INTO " . Permissions::TABLE_NAME .
            " (`permCode`, `permName`, `permDesc`) VALUES (:permCode, :permName, :permDesc);";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":permName", $perm->getPermName(), PDO::PARAM_STR);
        $statement->bindValue(":description", $perm->getPermDesc(), PDO::PARAM_STR);
        $statement->bindValue(":permCode", $perm->getPermCode(), PDO::PARAM_STR);
        $statement->execute();
        $new_id = (int) $connection->lastInsertId();
        return $this->getById($new_id);
    }
    
    /**
     * @param Permissions $perm
     * @return Permissions
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function update(Permissions $perm) : Permissions {
        $perm->validateForDbUpdate();
        $query =
            "UPDATE `" . Permissions::TABLE_NAME .
            "` SET `permCode` = :permCode, `permName` = :permName, `permDesc` = :permDesc WHERE `permId` = :permId ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":permCode", $perm->getPermCode(), PDO::PARAM_STR);
        $statement->bindValue(":permName", $perm->getPermName(), PDO::PARAM_STR);
        $statement->bindValue(":permDesc", $perm->getPermDesc(), PDO::PARAM_STR);
        $statement->bindValue(":id", $perm->getPermId(), PDO::PARAM_INT);
        $statement->execute();
        return $this->getById($perm->getPermId());
    }
    
    /**
     * @param Permissions $perm
     * @return void
     * @throws ValidationException
     */
    public function delete(Permissions $perm) : void {
        $perm->validateForDbDelete();
        $this->deleteById($perm->getPermId());
    }
    
    /**
     * @throws RuntimeException
     */
    public function deleteById(int $permId) : void {
        $query =
            "DELETE FROM `" . Permissions::TABLE_NAME .
            "` WHERE `permId` = :permId ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":permId", $permId, PDO::PARAM_INT);
        $statement->execute();
    }
    
    /**
     * @param BookDTO $perm
     * @return array
     */
    public function getUsersByPermit(Permissions $perm) : array {
        return $this->getUsersByPermitId($perm->getPermId());
    }
    
    public function getUsersByPermitId(int $id) : array {
        $query = "SELECT a.* FROM " . Users::TABLE_NAME . " a JOIN " . UsersPermits::TABLE_NAME .
            " ab ON a.usrId = ab.usrId JOIN " . Permissions::TABLE_NAME .
            " b ON ab.permId = b.permId WHERE b.permId = :permId ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":permId", $id, PDO::PARAM_INT);
        $statement->execute();
        
        $result_set = $statement->fetchAll(PDO::FETCH_ASSOC);
        $author_array = [];
        foreach ($result_set as $author_record) {
            $author_array[] = Users::fromDbArray($author_record);
        }
        return $author_array;
    }
}