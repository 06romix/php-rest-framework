<?php
declare(strict_types=1);

namespace Dev256\Rest\Endpoint;

use Dev256\Framework\Reflection\MethodsMap;
use Dev256\Framework\Reflection\TypeProcessor;

/**
 * Deserialize arguments from API requests.
 */
class ServiceInputProcessor
{

    public function __construct(
        private TypeProcessor $typeProcessor,
        private MethodsMap $methodsMap,
    ) {}

    /**
     * @throws \Dev256\Rest\Endpoint\InputException
     */
    public function process(string $serviceClassName, string $serviceMethodName, array $inputArray): array
    {
        $inputData = [];
        $inputError = [];
        foreach ($this->methodsMap->getMethodParams($serviceClassName, $serviceMethodName) as $param) {
            $paramName = $param[MethodsMap::METHOD_META_NAME];
            if (isset($inputArray[$paramName])) {
                $paramValue = $inputArray[$paramName];
                $inputData[] = $this->convertValue($paramValue, $param[MethodsMap::METHOD_META_TYPE]);
            } else {
                if ($param[MethodsMap::METHOD_META_HAS_DEFAULT_VALUE]) {
                    $inputData[] = $param[MethodsMap::METHOD_META_DEFAULT_VALUE];
                } else {
                    $inputError[] = $paramName;
                }
            }
        }
        $this->processInputError($inputError);
        return $inputData;
    }

    public function convertValue(string|int|bool $data, string $type): string|int|bool
    {
        return $this->typeProcessor->processSimpleAndAnyType($data, $type);
    }

    /**
     * Process an input error
     *
     * @param array $inputError
     * @return void
     * @throws InputException
     */
    protected function processInputError(array $inputError): void
    {
        if (!empty($inputError)) {

            foreach ($inputError as $errorParamField) {
                throw new \Dev256\Rest\Endpoint\InputException(
                    sprintf('"%s" is required. Enter and try again.', $errorParamField)
                );
            }
        }
    }
}
