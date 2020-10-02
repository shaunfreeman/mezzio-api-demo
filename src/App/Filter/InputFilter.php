<?php

declare(strict_types=1);

namespace Cms\App\Filter;

class InputFilter
{
    protected string $object;
    protected array $filterDefinition;
    protected array $validateDefinition;

    public function getData(): object
    {

    }

    public function validate() {}

    public function filter(array $data): array
    {
        $returnData = [];

        foreach ($data as $key => $value) {
            $filters = $this->filterDefinition[$key];
            $sanitisedValue = $value;

            foreach ($filters as $filter) {
                $filterName = $filter['filter'];
                $options = [
                    'options' => $filter['options'] ?? null,
                    'flags'   => $filter['flags'] ?? null,
                ];
                $sanitisedValue = filter_var($sanitisedValue, $filterName, $options);
                $returnData[$key] = $sanitisedValue;
            }
        }

        return $returnData;
    }
}
