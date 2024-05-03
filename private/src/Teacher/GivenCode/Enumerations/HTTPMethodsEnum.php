<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project HTTPMethodsEnum.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-14
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\GivenCode\Enumerations;

use JetBrains\PhpStorm\Pure;

/**
 * TODO: Enumeration documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-03-16
 */
enum HTTPMethodsEnum: string {
    case GET = "GET";
    case POST = "POST";
    case PUT = "PUT";
    case DELETE = "DELETE";
    
    /**
     * TODO: Function documentation
     *
     * @static
     * @param string $methodString
     * @return \GivenCode\Enumerations\HTTPMethodsEnum
     *
     * @author Marc-Eric Boury
     * @since  2024-03-16
     */
    #[Pure] public static function getValue(string $methodString) : \Teacher\GivenCode\Enumerations\HTTPMethodsEnum {
        return self::from(strtoupper(trim($methodString)));
    }
}
