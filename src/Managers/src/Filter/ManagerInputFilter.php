<?php

declare(strict_types=1);

namespace Managers\Filter;

use App\Filter\InputFilter;
use App\Filter\Sanitizer;
use App\Filter\Validator;
use App\ValueObject\Uuid;
use Managers\Entity\ManagerDto;
use Managers\Repository\ManagerRepositoryInterface;
use PDOException;

final class ManagerInputFilter extends InputFilter
{
    protected string $object = ManagerDto::class;

    private ManagerRepositoryInterface $managerRepository;

    private ?Uuid $uuid;

    public function __construct(array $data, ManagerRepositoryInterface $managerRepository, ?Uuid $uuid)
    {
        $this->uuid                 = $uuid;
        $this->managerRepository    = $managerRepository;

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
        ];

        parent::__construct($data);
    }

    protected function validateUniqueEmail(string $value): bool
    {
        $currentEmail = '';

        if ($this->uuid instanceof Uuid) {
            $user           = $this->managerRepository->find($this->uuid);
            $currentEmail   = $user->getEmail();
        }

        try {
            $this->managerRepository->findByEmail($value, $currentEmail);
        } catch (PDOException $exception) {
            return true;
        }

        return false;
    }
}
