<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration\Traits\Sql;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Exception;
use ReflectionClass;

/**
 * Trait InitSql
 * @package UdgBurmesterTheme\Migration\Traits\Sql
 */
trait InitSql
{
    /**
     * SQL STATEMENTS
     *
     * @return string
     */
    private function getSql(): string
    {
        return file_get_contents($this->getSqlFilePath());
    }

    /**
     * @return string
     */
    private function getClassName(): string
    {
        try {
            $className = (new ReflectionClass($this))->getShortName();
        } catch (Exception $e) {
            $className = get_class($this);

            $classNameSegments = explode('\\', $className);

            $className = array_pop($classNameSegments);
        }

        return $className;
    }

    /**
     * @return string
     */
    private function getSqlFileName(): string
    {
        return $this->getClassName() . '.sql';
    }

    /**
     * @return string
     */
    private function getSqlFilePath(): string
    {
        $fileName = $this->getSqlFileName();

        try {
            $sqlFilePath = dirname((new ReflectionClass($this))->getFileName()) ;
        } catch (Exception $e) {
            $sqlFilePath = dirname(dirname(dirname(__FILE__)));
        }

        $sqlFilePath = $sqlFilePath . DIRECTORY_SEPARATOR . $fileName;

        return str_replace('/', DIRECTORY_SEPARATOR, $sqlFilePath);
    }


    /**
     * QUERIES
     *
     * @param Connection $connection
     * @param string $languageName
     * @return string|null
     */
    private function getLanguageIdByName(Connection $connection, string $languageName): ?string
    {
        return $this->getIdFromQuery(
            $connection,
            'SELECT `id` FROM `language` WHERE `name` = :itemName',
            ['itemName' => $languageName]
        );
    }

    /**
     * @param Connection $connection
     * @param string $query
     * @param array $parameters
     * @return string|null
     */
    private function getIdFromQuery(Connection $connection, string $query, array $parameters): ?string
    {
        try {
            return (string)$connection->fetchColumn($query, $parameters);
        } catch (DBALException $e) {
            return null;
        }
    }
}
