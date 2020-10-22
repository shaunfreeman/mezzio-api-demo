<?php

declare(strict_types=1);

namespace Cms\Orders\Repository\Pdo;

use PDO;
use Cms\App\ValueObject\Uuid;
use Cms\Orders\Entity\OrderEntity;
use Cms\Orders\Repository\OrderRepositoryInterface;

final class OrderRepository implements OrderRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function find(Uuid $uuid): OrderEntity
    {
        $statement = $this->pdo->prepare(
            'SELECT `doc` FROM `orders` WHERE id=:id LIMIT 1'
        );
        $statement->execute([ 'id' => (string) $uuid ]);

        return OrderEntity::fromArray($statement->fetch(PDO::FETCH_ASSOC));
    }

    public function findAll(int $offset = 0, int $limit = 25): array
    {
        $statement = $this->pdo->prepare(
            'SELECT `doc` FROM `orders` ORDER BY modified DESC LIMIT :offset, :limit'
        );

        $statement->bindValue('offset', $offset, PDO::PARAM_INT);
        $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        $rows = [];

        foreach ($statement->fetchAll() as $row) {
            $rows[] = json_decode($row['doc'], true);
        }

        return $rows;
    }

    public function save(OrderEntity $orderEntity): OrderEntity
    {
        $order      = $orderEntity->getArrayCopy();
        $statement  = $this->pdo->prepare('
            INSERT INTO `orders` (`id`, `claim_number`, `job_number`, `doc`, `modified`, `created`) 
            VALUES (:id, :claim_numer, :job_number, :doc, :modified, :created) 
            ON DUPLICATE KEY UPDATE doc = :doc, modified = :modified
        ');

        $statement->execute([
            'id'            => $order['id'],
            'claim_number'  => $order['claim_number'],
            'job_number'    => $order['job_number'],
            'doc'           => json_encode($order['doc']),
            'modified'      => $order['modified'],
            'created'       => $order['created'],
        ]);

        return $orderEntity;
    }

    public function delete(Uuid $uuid): bool
    {
        $statement = $this->pdo->prepare(
            'DELETE FROM `orders` WHERE id=:id'
        );
        $statement->execute([ 'id' => (string) $uuid ]);

        if ($statement->rowCount() === 1) {
            return true;
        }

        return false;
    }
}
