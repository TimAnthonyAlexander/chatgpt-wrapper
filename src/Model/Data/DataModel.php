<?php

declare(strict_types=1);

namespace Timanthonyalexander\Chatgptwrapper\Model\Data;

abstract class DataModel
{
    public function toArray(): array
    {
        $objectVars = get_object_vars($this);
        return self::turnIntoArrayRecursive($objectVars);
    }

    private static function turnIntoArrayRecursive(array $data): array
    {
        $array = [];
        foreach ($data as $key => $value) {
            if ($value instanceof self) {
                $array[$key] = $value->toArray();
                continue;
            }
            if (is_array($value)) {
                $array[$key] = self::turnIntoArrayRecursive($value);
                continue;
            }
            $array[$key] = $value;
        }
        return $array;
    }
}
