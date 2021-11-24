<?php
declare(strict_types=1);

namespace Dev256\Rest\Endpoint;

use Dev256\Framework\Reflection\DataObjectProcessor;
use Dev256\Framework\Reflection\MethodsMap;
use Dev256\Framework\Reflection\TypeProcessor;
use Laminas\Code\Reflection\ClassReflection;

class ServiceOutputProcessor
{
    public function __construct(
        private MethodsMap $methodsMapProcessor,
        private TypeProcessor $typeProcessor,
        private DataObjectProcessor $dataObjectProcessor
    ) {}

    public function process(mixed $data, $serviceClassName, $serviceMethodName): array
    {
        $dataType = $this->methodsMapProcessor->getMethodReturnType($serviceClassName, $serviceMethodName);

        if (class_exists($serviceClassName) || interface_exists($serviceClassName)) {
            $sourceClass = new ClassReflection($serviceClassName);
            $dataType = $this->typeProcessor->resolveFullyQualifiedClassName($sourceClass, $dataType);
        }

        return $this->convertValue($data, $dataType);
    }

    public function convertValue($data, $type)
    {
        if (is_array($data)) {
            $result = [];
            $arrayElementType = substr($type, 0, -2);
            foreach ($data as $datum) {
                if (is_object($datum)) {
                    $datum = $this->dataObjectProcessor->buildOutputDataArray($datum, $arrayElementType);
                }
                $result[] = $datum;
            }
            return $result;
        }

        if (is_object($data)) {
            return $this->dataObjectProcessor->buildOutputDataArray($data, $type);
        }

        if ($data === null) {
            return [];
        }

        /** No processing is required for scalar types */
        return $data;
    }
}
