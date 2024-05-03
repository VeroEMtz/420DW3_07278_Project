<?php
declare(strict_types=1);

namespace public\CodeBlockIncVEMC\src\DAO;

use PDO;
use public\CodeBlockIncVEMC\src\DTO\Users;
use public\CodeBlockIncVEMC\src\services\DBConnectionService;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 *
 */
class UsersDAO {
    
    /**
     * @throws ValidationException
     * @throws RuntimeException
     */
    
    public function getAll() : array {
        $query = "SELECT * FROM " . Users::TABLE_NAME . ";";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->execute();
        $result_set = $statement->fetchAll(PDO::FETCH_ASSOC);
        $users = [];
        
        foreach ($result_set as $result) {
            $users[] = Users::fromDbArray($result);
        }
        return $users;
        
    }
    
    public function getById(string $usrId) : ?Users {
        $query = "SELECT * FROM " . Users::TABLE_NAME . " WHERE usrId = :usrId ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":usrId", $usrId, PDO::PARAM_STR);
        $statement->execute();
        $users_array = $statement->fetch(PDO::FETCH_ASSOC);
        return Users::fromDbArray($users_array);
    }
    
    public function insert(Users $user) : Users {
        $user->validateForDbCreation();
        $query =
            "INSERT INTO " . Users::TABLE_NAME . " ('usrName', 'password') VALUES (:usrName, :password);";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":usrName", $user->getUsrName(), PDO::PARAM_STR);
        $statement->bindValue(":password", $user->getPassword(), PDO::PARAM_STR);
        $statement->execute();
        $new_usrId = $connection->lastInsertId();
        return $this->getByUsrId($new_usrId);
    }
    
    public function update(Users $users) : Users {
        $users->validateForDbUpdate();
        $query =
            "UPDATE " . Users::TABLE_NAME .
            " SET `usrName` = :usrName, `password` = :password WHERE `id` = :id ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":usrName", $users->getusrName(), PDO::PARAM_STR);
        $statement->bindValue(":password", $users->getpassword(), PDO::PARAM_STR);
        $statement->bindValue(":id", $users->getUsrId(), PDO::PARAM_INT);
        $statement->execute();
        return $this->getById($users->getId());
    }
    
    public function delete(Users $users) : void {
        $users->validateForDbDelete();
        $query =
            "DELETE FROM " . Users::TABLE_NAME . " WHERE `usrId` = :id ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":id", $users->getUsrId(), PDO::PARAM_INT);
        $statement->execute();
    }
    
    public function getPermsByUser(Users $users) : array {
        if (empty($users->getUsrId())) {
            throw new ValidationException("Cannot get the book records for an author with no set [id] property value.");
        }
        return $this->getPermsByUserId($users->getUsrId());
        
    }
    
    public function getPermsByUserId(int $id) : array {
        $query = "SELECT b.* FROM " . Users::TABLE_NAME . " a JOIN " . UsersPermissionsDAO::TABLE_NAME .
            " ab ON a.usrId = ab.usrId JOIN " . Permissions::TABLE_NAME . " b ON ab.PermId = b.permId WHERE a.usrId = :usrId ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":usrId", $id, PDO::PARAM_INT);
        $statement->execute();
        
        $result_set = $statement->fetchAll(PDO::FETCH_ASSOC);
        $books_array = [];
        foreach ($result_set as $book_record_array) {
            $books_array[] = Permissions::fromDbArray($book_record_array);
        }
        return $books_array;
        
    }
}