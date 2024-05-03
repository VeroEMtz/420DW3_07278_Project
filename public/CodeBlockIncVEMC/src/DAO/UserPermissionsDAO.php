<?php
declare(strict_types=1);
/**
 * @author Veronica Martinez
 * @since  2024-05-03
 */

namespace public\CodeBlockIncVEMC\src\DAO;

use PDO;
use public\CodeBlockIncVEMC\src\services\DBConnectionService;
use Teacher\GivenCode\Exceptions\RuntimeException;

/**
 * @author Veronica Martinez
 * @since  2024-05-03
 */
class UserPermissionsDAO {
    /**
     *
     */
    public const TABLE_NAME = "users_permissions";
    private const CREATE_QUERY = "INSERT INTO" . self::TABLE_NAME .
    " ('usrId', 'permId') VALUES (:usrId, :permId);";
    
    public function __construct() {}
    
    /**
     * @param int $usrId
     * @param int $permId
     * @return void
     * @throws RuntimeException
     */
    public function createForUserAndPermission(int $usrId, int $permId) : void {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::CREATE_QUERY);
        $statement->bindValue(":usrId", $usrId, PDO::PARAM_INT);
        $statement->execute();
    }
    
    /**
     * @param int $permId
     * @return void
     * @throws RuntimeException
     */
    public function deleteAllByPermId(int $permId) : void {
        $query = "DELETE FROM" . self::TABLE_NAME . " WHERE 'permId' = :permId;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":permId", $permId, PDO::PARAM_INT);
        $statement->execute();
    }
    
}