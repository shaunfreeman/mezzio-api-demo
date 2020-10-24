<?php

declare(strict_types=1);

namespace Cms\Test\Users\Entity;

use Cms\Users\Entity\UserEntity;
use Exception;
use PHPUnit\Framework\TestCase;

class UserEntityTest extends TestCase
{
    private array $data = [
        'name' => 'Joe Blogs',
        'email' => 'joe_blogs@example.com',
        'password' => 'password',
        'role' => 'admin',
    ];

    /**
     * @throws Exception
     */
    public function testCanCreateFromArray()
    {
        $user = UserEntity::fromArray($this->data);
        $this->assertInstanceOf(UserEntity::class, $user);
    }

    /**
     * @throws Exception
     */
    public function testCanGetArrayCopy()
    {
        $user = UserEntity::fromArray($this->data);
        $this->assertCount(7, $user->getArrayCopy());
    }
}
