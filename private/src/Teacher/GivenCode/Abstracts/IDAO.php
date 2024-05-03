<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project IDAO.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-16
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\GivenCode\Abstracts;

use Teacher\GivenCode\Abstracts\AbstractDTO;

/**
 * Interface for DAO-type objects enforcing basic database operations methods.
 *
 * @author Marc-Eric Boury
 * @since  2024-03-16
 */
interface IDAO {
    
    /**
     * Retrieves a record of a certain DTO entity from the database and returns
     * an appropriate DTO object instance.
     *
     * @param int $id The identifier value of the record to obtain.
     * @return AbstractDTO|null The created object DTO instance or null if no record was found for the specified id.
     *
     * @author Marc-Eric Boury
     * @since  2024-03-17
     */
    public function getById(int $id) : ?AbstractDTO;
    
    /**
     * Creates a record for a certain DTO entity in the database.
     * Returns an updated appropriate DTO object instance.
     *
     * @param AbstractDTO $dto The {@see AbstractDTO} instance to create a record of.
     * @return AbstractDTO An updated {@see AbstractDTO} instance.
     *
     * @author Marc-Eric Boury
     * @since  2024-03-17
     */
    public function create(AbstractDTO $dto) : AbstractDTO;
    
    /**
     * Updates the record of a certain DTO entity in the database.
     * Returns an updated appropriate DTO object instance.
     *
     * @param AbstractDTO $dto The {@see AbstractDTO} instance to update the record of.
     * @return AbstractDTO An updated {@see AbstractDTO} instance.
     *
     * @author Marc-Eric Boury
     * @since  2024-03-17
     */
    public function update(AbstractDTO $dto) : AbstractDTO;
    
    /**
     * Deletes the record of a certain DTO entity in the database.
     *
     * @param AbstractDTO $dto The {@see AbstractDTO} instance to delete the record of.
     * @return void
     *
     * @author Marc-Eric Boury
     * @since  2024-03-17
     */
    public function delete(AbstractDTO $dto) : void;
    
    /**
     * Deletes the record of a certain DTO entity in the database based on its identifier.
     *
     * @param int $id The identifier of the DTO entity to delete
     * @return void
     *
     * @author Marc-Eric Boury
     * @since  2024-03-17
     */
    public function deleteById(int $id) : void;
    
}