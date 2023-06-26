<?php

namespace App\Services\Checker;

    use DateTime;

class Checker
{
    private const OLD = 0;
    private const NEW = 1;

    public function isChanged(array $changedFields, array $fields): bool
    {
        $result = false;
        if (count($changedFields) > 0) {
            foreach ($fields as $field) {
                if (isset($changedFields[$field])) {
                    $oldValue = $changedFields[$field][self::OLD];
                    $newValue = $changedFields[$field][self::NEW];
                    if (strpos($field, 'time') !== false) {
                        /** @var DateTime $oldValue */
                        $oldValue = $oldValue->getTimestamp();
                        /** @var DateTime $newValue */
                        $newValue = $newValue->getTimestamp();
                    } elseif (strpos($field, 'date') !== false) {
                        /** @var DateTime $oldValue */
                        $oldValue = $oldValue->setTime(0, 0)->getTimestamp();
                        /** @var DateTime $newValue */
                        $newValue = $newValue->setTime(0, 0)->getTimestamp();
                    }
                    if ($oldValue !== $newValue) {
                        return true;
                    }
                }
            }
        }

        return $result;
    }
}