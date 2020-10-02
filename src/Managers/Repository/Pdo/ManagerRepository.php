<?php

declare(strict_types=1);

namespace Cms\Managers\Repository\Pdo;

use Exception;
use PDO;
use Cms\App\ValueObject\Uuid;
use Cms\Managers\Repository\ManagerRepositoryInterface;

final class ManagerRepository implements ManagerRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param Uuid $uuid
     *
     * @return array
     * @throws Exception
     */
    public function find(Uuid $uuid): array
    {
        $statement = $this->pdo->prepare(
            'SELECT * FROM `managers` WHERE id=:id LIMIT 1'
        );
        $statement->execute([ 'id' => (string) $uuid ]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            throw new Exception(sprintf('id: \'%s\' not found', $uuid));
        }

        return $result;
    }

    public function findAll(): array
    {
        $statement = $this->pdo->prepare(
            'SELECT * FROM `managers` ORDER BY name ASC'
        );
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        $statement = $this->pdo->prepare('
            INSERT INTO `managers` 
                (`id`, `name`, `email`, `modified`, `created`)
            VALUES 
                (:id, :name, :email, :modified, :created)
        ');
        $statement->execute($data);

        if ($statement->rowCount() === 1) {
            return true;
        }

        return false;
    }

    public function update(array $data): bool
    {
        unset($data['created']);

        $statement = $this->pdo->prepare('
            UPDATE `managers` 
            SET `name` = :name, `email` = :email, `modified` = :modified
            WHERE `id` = :id
        ');
        $statement->execute($data);

        if ($statement->rowCount() === 1) {
            return true;
        }

        return false;
    }

    public function delete(Uuid $uuid): bool
    {
        $statement = $this->pdo->prepare(
            'DELETE FROM `managers` WHERE id=:id'
        );
        $statement->execute([ 'id' => (string) $uuid ]);

        if ($statement->rowCount() === 1) {
            return true;
        }

        return false;
    }
}
