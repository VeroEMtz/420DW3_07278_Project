<?php

namespace public\CodeBlockIncVEMC\src\services;

use Exception;
use public\CodeBlockIncVEMC\src\DAO\UsersDAO;
use public\CodeBlockIncVEMC\src\DTO\Users;
use Teacher\GivenCode\Abstracts\IService;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;

class UsersService implements IService {
    private UsersDAO $dao;
    
    /**
     *
     */
    public function __construct() {
        $this->dao = new UsersDAO();
    }
    
    /**
     * @return array
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function getAllUsers() : array {
        return $this->dao->getAll();
    }
    
    /**
     * @param int $id
     * @return Users|null
     */
    public function getUserById(int $id) : ?Users {
        $user = $this->dao->getById($id);
        $user?->loadPermits();
        return $user;
    }
    
    /**
     * @throws RuntimeException
     */
    public function createUser(string $usrName, string $password, string $email) : Users {
        try {
            $user = Users::fromValues($usrName, $password, $email);
            return $this->dao->insert($user);
            
        } catch (Exception $excep) {
            throw new RuntimeException("Failure to create user [$usrName, $password, $email].", $excep->getCode(),
                                       $excep);
        }
    }
    
    /**
     * @param int    $usrId
     * @param string $usrName
     * @param string $password
     * @param string $email
     * @return Users
     * @throws RuntimeException
     */
    public function updateUsers(int $usrId, string $usrName, string $password, string $email) : Users {
        try {
            $connection = DBConnectionService::getConnection();
            $connection->beginTransaction();
            
            try {
                $user = $this->dao->getById($usrId);
                if (is_null($user)) {
                    throw new Exception("Author id# [$usrId] not found in the database.");
                }
                $user->setUsrName($usrName);
                $user->setPassword($password);
                $user->setEmail($email);
                $result = $this->dao->update($user);
                $connection->commit();
                return $result;
                
            } catch (Exception $inner_excep) {
                $connection->rollBack();
                throw $inner_excep;
            }
            
        } catch (Exception $excep) {
            throw new RuntimeException("Failure to update author id# [$usrId].", $excep->getCode(), $excep);
        }
    }
    
    /**
     * @param int $usrId
     * @return void
     * @throws RuntimeException
     */
    public function deleteUsersById(int $usrId) : void {
        try {
            
            $connection = DBConnectionService::getConnection();
            $connection->beginTransaction();
            
            try {
                $user = $this->dao->getById($usrId);
                if (is_null($user)) {
                    throw new Exception("Author id# [$usrId] not found in the database.");
                }
                $this->dao->delete($user);
                $connection->commit();
                
            } catch (Exception $inner_excep) {
                $connection->rollBack();
                throw $inner_excep;
            }
            
        } catch (Exception $excep) {
            throw new RuntimeException("Failure to delete author id# [$usrId].", $excep->getCode(), $excep);
        }
    }
    
    /**
     * @param Users $user
     * @return array
     */
    public function getUserPermits(Users $user) : array {
        return $this->getUserPermitsByUsrId($user->getId());
    }
    
    /**
     * @param int $usrId
     * @return array
     */
    public function getUserPermitsByUsrId(int $usrId) : array {
        return $this->dao->getPermsByUserId($usrId);
    }
}