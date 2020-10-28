<?php

declare(strict_types=1);

namespace Core\Filter;

use Core\Entity\DtoInterface;

class InputFilter
{
    protected string $object;
    protected array $sanitizeDefinition;
    protected array $validateDefinition;
    protected array $dirtyData;
    protected array $cleanData;
    private bool $isValid = false;
    private bool $hasBeenValidated = false;

    public function __construct(array $data)
    {
        $this->dirtyData = $data;
        $this->sanitize();
    }

    public function getData(): DtoInterface
    {
        $object = new $this->object();

        foreach ($this->cleanData as $key => $value) {
            if (property_exists($object, $key)) {
                $object->{$key} = $value;
            }
        }

        return $object;
    }

    public function isValid(): bool
    {
        if (!$this->hasBeenValidated) {
            $this->validate();
        }

        return $this->isValid;
    }

    private function validate(): void
    {
        $errors = [];

        foreach ($this->cleanData as $key => $value) {
            $validators     = $this->validateDefinition[$key];
            $inputErrors    = $this->validateValue($value, $validators);

            if (in_array(false, $inputErrors)) {
                $this->setErrorMessage($key, $inputErrors);
                $errors[$key] = $inputErrors;
            }
        }

        $this->isValid = empty($errors);
        $this->hasBeenValidated = true;
    }

    private function validateValue(string $value, array $validators)
    {
        $errors = [];

        foreach ($validators as $key => $validator) {
            $errors[$key] = $this->filter($value, $validator);
        }

        return $errors;
    }

    private function sanitize(): void
    {
        $cleanData = [];

        foreach ($this->dirtyData as $key => $value) {
            $sanitizers         = $this->sanitizeDefinition[$key];
            $cleanData[$key]    = $this->sanitizeValue($value, $sanitizers);
        }

        $this->cleanData = $cleanData;
    }

    private function sanitizeValue(string $value, array $sanitizers)
    {
        foreach ($sanitizers as $sanitizer) {
            $value = $this->filter($value, $sanitizer);
        }

        return $value;
    }

    private function filter(string $value, array $filter)
    {
        $filterName = $filter['filter'];
        $options    = [
            'options' => $filter['options'] ?? null,
            'flags'   => $filter['flags'] ?? null,
        ];

        return filter_var($value, $filterName, $options);
    }

    private function setErrorMessage(string $input, array $inputErrors): void
    {
        foreach ($inputErrors as $key => $value) {
            if (!$value) {
                $this->cleanData['errors'][$input][] = $this->validateDefinition[$input][$key]['error_message'];
            }
        }
    }
}
