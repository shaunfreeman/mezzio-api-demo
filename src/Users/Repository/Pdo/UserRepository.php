<?php

declare(strict_types=1);

namespace Cms\Users\Repository\Pdo;

use Exception;
use PDO;
use PDOException;
use Cms\App\ValueObject\Uuid;
use Cms\Users\Entity\UserEntity;
use Cms\Users\Repository\UserRepositoryInterface;

final class UserRepository implements UserRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param string $email
     * @return UserEntity
     * @throws Exception
     */
    public function findByEmail(string $email): UserEntity
    {
        $statement = $this->pdo->prepare(
            'SELECT * FROM `users` WHERE email=:email LIMIT 1'
        );
        $statement->execute([ 'email' => $email ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new PDOException(sprintf('\'%s\' is not found in database.', $email));
        }

        return UserEntity::fromArray($result);
    }

    /**
     * @param Uuid $uuid
     * @return array
     * @throws Exception
     */
    public function find(Uuid $uuid): array
    {
        $statement = $this->pdo->prepare(
            'SELECT * FROM `users` WHERE id=:id LIMIT 1'
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
            'SELECT * FROM `users` ORDER BY modified DESC'
        );
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        $statement = $this->pdo->prepare('
            INSERT INTO `users` 
                (`id`, `name`, `email`, `password`, `role`, `modified`, `created`)
            VALUES 
                (:id, :name, :email, :password, :role, :modified, :created)
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
            UPDATE `users`
            SET `name` = :name, `email` = :email, `password` = :password, `role` = :role, `modified` = :modified
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
            'DELETE FROM `users` WHERE id=:id'
        );
        $statement->execute([ 'id' => (string) $uuid ]);

        if ($statement->rowCount() === 1) {
            return true;
        }

        return false;
    }
}
