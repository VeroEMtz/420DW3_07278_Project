<?php

namespace public\CodeBlockIncVEMC\src\services;

use Exception;
use JsonException;
use PDO;
use PDOException;
use Teacher\GivenCode\Abstracts\IService;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;

class DBConnectionService implements IService {
    private const CONFIG_FILENAME = "dbconfig.json";
    private static ?PDO $connection = null;
    
    /**
     * @throws RuntimeException
     */
    public static function getConnection() : PDO {
        try {
            if (!(self::$connection instanceof PDO)) {
                self::$connection = self::createConnection();
            }
            return self::$connection;
        } catch (Exception $exception) {
            throw new RuntimeException("Failure to obtain a connection to the database.", 0, $exception);
        }
    }
    
    /**
     * @throws Exception
     */
    private static function createConnection() : PDO {
        try {
            $conn_config = self::readConfigFile();
            $dsn = $conn_config["db_type"] . ":host=" . $conn_config["db_host"] . ";port=" . $conn_config["db_port"] .
                ";dbname=" . $conn_config["db_dbname"];
            $connection = new PDO($dsn, $conn_config["db_user"], $conn_config["db_password"]);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connection;
            
        } catch (PDOException $exception) {
            throw new Exception("PDO failed to establish a connection to the database: " . $exception->getMessage());
            
        } catch (Exception $excep) {
            throw new Exception("Failed to create a connection to the database.", 0, $excep);
        }
    }
    
    /**
     * @throws Exception
     */
    private static function readConfigFile() : array {
        try {
            $config_file_path = PRJ_CONFIG_DIR . self::CONFIG_FILENAME;
            //$config_file_path = "../../Config";
            if (!file_exists($config_file_path)) {
                throw new Exception("Database connection configuration file at [$config_file_path] was not found.");
            }
            if (!is_readable($config_file_path)) {
                throw new Exception("Database connection configuration file at [$config_file_path] is not readable. Check file access permissions.");
            }
            $json_string = file_get_contents($config_file_path);
            try {
                $config_array = json_decode($json_string, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException $json_excep) {
                throw new Exception("Database connection configuration file at [$config_file_path] is not valid JSON.", 0, $json_excep);
            }
            self::validateConfigArray($config_array);
            return $config_array;
            
        } catch (Exception $excep) {
            throw new Exception("Failed to read database connection configuration file.", 0, $excep);
        }
    }
    
    /**
     * @throws Exception
     */
    private static function validateConfigArray(array $config_array) : void {
        try {
            if (empty($config_array["db_type"])) {
                throw new ValidationException("Database connection configurations lack a required [db_type] entry.");
            }
            if (empty($config_array["db_host"])) {
                throw new ValidationException("Database connection configurations lack a required [db_host] entry.");
            }
            if (empty($config_array["db_port"])) {
                throw new ValidationException("Database connection configurations lack a required [db_port] entry.");
            }
            if (!is_numeric($config_array["db_port"])) {
                throw new ValidationException("Database connection configurations [db_port] value is not numeric.");
            }
            $int_port = (int) $config_array["db_port"];
            if ($int_port < 0 || $int_port > 65535) {
                throw new ValidationException("Database connection configurations [db_port] value is not a valid port number (0 - 65535).");
            }
            if (empty($config_array["db_dbname"])) {
                throw new ValidationException("Database connection configurations lack a required [db_dbname] entry.");
            }
            if (empty($config_array["db_user"])) {
                throw new ValidationException("Database connection configurations lack a required [db_user] entry.");
            }
            // Note: use isset() instead of empty() because pw can be empty string
            if (!isset($config_array["db_password"])) {
                throw new ValidationException("Database connection configurations lack a required [db_password] entry.");
            }
            
        } catch (Exception $excep) {
            throw new Exception("Invalid database connection configuration data.", 0, $excep);
        }
    }
}