<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project AbstractDTO.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-14
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\GivenCode\Abstracts;


use Teacher\GivenCode\Abstracts\DTOTrait;
use Teacher\GivenCode\Abstracts\IDTO;
use http\Exception\RuntimeException;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-03-14
 * @property mixed $usrName
 * @property mixed $password
 */
abstract class AbstractDTO implements IDTO {
    use DTOTrait;
    
    /**
     * Constructor for {@see \GivenCode\Abstracts\\GivenCode\Abstracts\\Teacher\GivenCode\Abstracts\AbstractDTO}.
     */
    public function __construct() {}
    
    /**
     * @inheritDoc
     * @throws RuntimeException If the primary key value is not set.
     */
    public function getPrimaryKeyValue() : int {
        if (empty($this->id)) {
            throw new RuntimeException("Primary key value is not set.");
        }
        return $this->id;
    }
    
    /**
     * @inheritDoc
     */
    public function getPrimaryKeyColumnName() : string {
        return self::$primaryKeyColumnName;
    }
    
    /**
     * @inheritDoc
     */
    abstract public function getDatabaseTableName() : string;
    
}