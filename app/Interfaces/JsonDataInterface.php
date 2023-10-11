<?php

namespace App\Interfaces;

interface JsonDataInterface
{
/**
 * Set the path to the JSON file.
 *
 * @param string $path The path to the JSON file.
 *
 * @return bool Returns true if the path was successfully set, false otherwise.
 */
public function setJsonFilePath(string $path): bool;

/**
 * Insert data from the JSON file into the database.
 *
 * @return bool Returns true if the data was successfully inserted, false otherwise.
 */
public function insertData(): bool;

}
