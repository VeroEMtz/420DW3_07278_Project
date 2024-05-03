<?php

namespace public\CodeBlockIncVEMC\src\services;

use public\CodeBlockIncVEMC\src\DAO\PermissionsDAO;
use public\CodeBlockIncVEMC\src\DTO\Permissions;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;

class PermissionService {
    
    private PermissionsDAO $dao;
    private UserPermissionsDAO $userPermissionsDao;
    
    public function __construct() {
        $this->dao = new PermissionsDAO();
        $this->userPermissionsDao = new UserPermissionsDAO();
    }
    
    /**
     * @return array
     * @throws RuntimeException
     */
    public function getAllPermissions() : array {
        return $this->dao->getAll();
    }
    
    /**
     * @param int $id
     * @return Permissions|null
     * @throws RuntimeException
     */
    public function getById(int $id) : ?Permissions {
        return $this->dao->getById($id);
    }
    
    /**
     * @param int         $permId
     * @param string      $permCode
     * @param string      $permName
     * @param string|null $permDesc
     * @return Permissions
     */
    public function create(int $permId, string $permCode, string $permName, ?string $permDesc = null) : Permissions {
        $instance = Permissions::fromValues($permCode, $permName, $permDesc);
        return $this->dao->insert($instance);
    }
    
    /**
     * @param int         $permId
     * @param string      $permCode
     * @param string      $permName
     * @param string|null $permDesc
     * @return Permissions
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function update(int $permId, string $permCode, string $permName, ?string $permDesc = null) : Permissions {
        // No transaction this time, contrary to the Example stack
        $instance = $this->dao->getById($permId);
        $instance->setPermCode($permCode);
        $instance->setPermName($permName);
        $instance->setPermDesc($permDesc);
        return $this->dao->update($instance);
    }
    
    /**
     * @param int $permId
     * @return void
     * @throws RuntimeException
     */
    public function delete(int $permId) : void {
        $this->dao->deleteById($permId);
    }
    
    /**
     * @param Permissions $perm
     * @return array
     */
    public function getPermissionsUsers(Permissions $perm) : array {
        return $this->getPermissionsUsersByPermId($perm->getPermId());
    }
    
    /**
     * @param int $permId
     * @return array
     */
    public function getPermissionsUsersByPermId(int $permId) : array {
        return $this->dao->getUsersByPermitId($permId);
    }
    
    /**
     * @param Permissions $perm
     * @return void
     */
    public function deleteAllPermitUserAssociationsForPermission(Permissions $perm) : void {
        $this->deleteAllPermitUserAssociationsForPermId($perm->getPermId());
    }
    
    /**
     * @param int $permId
     * @return void
     */
    public function deleteAllPermitUserAssociationsForPermId(int $permId) : void {
        $this->userPermissionsDao->deleteAllByPermId($permId);
    }
    
    /**
     * @param int $permId
     * @param int $usrId
     * @return void
     */
    public function associatePermissionWithUser(int $permId, int $usrId) : void {
        $this->userPermissionsDao->createForUserAndPermission($usrId, $permId);
    }
}