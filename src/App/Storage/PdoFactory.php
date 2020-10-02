<?php

declare(strict_types=1);

namespace Cms\App\Storage;

use ErrorException;
use PDO;
use PDOException;
use Psr\Container\ContainerInterface;

final class PdoFactory
{
    private string $dsnString = 'mysql:host=%s;dbname=%s;port=%d;charset=%s';

    /**
     * @param ContainerInterface $container
     * @return PDO
     * @throws ErrorException
     */
    public function __invoke(ContainerInterface $container): PDO
    {
        $dbOptions  = $container->get('config')['db'];

        try {
            $connection = new PDO(sprintf(
                $this->dsnString,
                $dbOptions['host'],
                $dbOptions['schema'],
                $dbOptions['port'],
                $dbOptions['charset']
            ), $dbOptions['user'], $dbOptions['password'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $exception) {
            throw new ErrorException(sprintf(
                'Database Connection Error: (%d) %s ',
                $exception->getCode(),
                $exception->getMessage()
            ));
        }

        return $connection;
    }
}
