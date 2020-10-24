<?php

declare(strict_types=1);

namespace Cms\Users\Filter;

use Cms\App\Entity\DtoInterface;
use Cms\App\Filter\InputFilter;
use Cms\App\Filter\Sanitizer;
use Cms\App\Filter\Validator;
use Cms\App\ValueObject\Uuid;
use Cms\Users\Entity\UserDto;
use Cms\Users\Entity\UserEntity;
use Cms\Users\Repository\UserRepositoryInterface;
use Cms\Users\ValueObject\Password;
use PDOException;

final class UserInputFilter extends InputFilter
{
    protected string $object = UserDto::class;

    private UserRepositoryInterface $userRepository;

    private ?Uuid $uuid;

    public function __construct(array $data, UserRepositoryInterface $userRepository, ?Uuid $uuid)
    {
        $this->uuid             = $uuid;
        $this->userRepository   = $userRepository;

        $this->validateDefinition = [
            'name' => [
                [
                    'filter'        => FILTER_CALLBACK,
                    'options'       => [Validator::class, 'required'],
                    'error_message' => 'Name is required.'
                ],
                [
                    'filter'        => FILTER_CALLBACK,
                    'options'       => Validator::stringLength(50),
                    'error_message' => 'name should not be longer than 50 characters.'
                ],
            ],
            'email' => [
                [
                    'filter'        => FILTER_CALLBACK,
                    'options'       => [Validator::class, 'required'],
                    'error_message' => 'Email is required.',
                ],
                ['filter' => FILTER_VALIDATE_EMAIL, 'error_message' => 'Email is not a valid email address.'],
                [
                    'filter' => FILTER_CALLBACK,
                    'options' => [$this, 'validateUniqueEmail'],
                    'error_message' => 'Email address already taken.'
                ],
            ],
            'password' => [
                [
                    'filter'        => FILTER_CALLBACK,
                    'options'       => [$this, 'validatePassword'],
                    'error_message' => 'Password must contain at least 1 uppercase, 1 lowercase, 1 digit and 1 special '
                                       . 'character and be at least 8 characters long.',
                ],
            ],
            'role' => [
                [
                    'filter'        => FILTER_CALLBACK,
                    'options'       => Validator::inArray(array_keys(UserEntity::USER_ROLES)),
                    'error_message' => 'Role type not supported.',
                ],
            ],
        ];

        $this->sanitizeDefinition = [
            'name' => [
                ['filter' => FILTER_SANITIZE_STRING],
                ['filter' => FILTER_CALLBACK, 'options' => [Sanitizer::class, 'trim']],
                ['filter' => FILTER_CALLBACK, 'options' => [Sanitizer::class, 'titleCase']],
            ],
            'email' => [
                ['filter' => FILTER_SANITIZE_EMAIL],
                ['filter' => FILTER_CALLBACK, 'options' => [Sanitizer::class, 'trim']],
            ],
            'password' => [
                ['filter' => FILTER_SANITIZE_STRING],
                ['filter' => FILTER_CALLBACK, 'options' => [Sanitizer::class, 'trim']],
            ],
            'role' => [
                ['filter' => FILTER_SANITIZE_STRING],
                ['filter' => FILTER_CALLBACK, 'options' => [Sanitizer::class, 'trim']],
            ],
        ];

        parent::__construct($data);
    }

    public function getData(): DtoInterface
    {
        if (!empty($this->cleanData['password'])) {
            $this->cleanData['password'] = Password::hash($this->cleanData['password']);
        }

        return parent::getData();
    }

    protected function validateUniqueEmail(string $value): bool
    {
        $currentEmail = '';

        if ($this->uuid instanceof Uuid) {
            $user           = $this->userRepository->find($this->uuid);
            $currentEmail   = $user->getEmail();
        }

        try {
            $this->userRepository->findByEmail($value, $currentEmail);
        } catch (PDOException $exception) {
            return true;
        }

        return false;
    }

    protected function validatePassword(string $value): bool
    {
        $result = true;

        if (!empty($value)) {
            $regexp = '/^(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$/';
            $result = (bool) preg_match($regexp, $value);
        }

        return $result;
    }
}
