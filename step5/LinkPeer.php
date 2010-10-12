<?php

    ...

    public function getIdFromDataset(array $data)
    {
        if (!isset($data[self::DB_ID_FIELD])) {
            return false;
        }
        return $data[self::DB_ID_FIELD];
    }

    public function translateDataset(array $data, $toDb = true)
    {
        $keyFrom = self::ID_FIELD;
        $keyTo = self::DB_ID_FIELD;
        if (!$toDb)  {
            $keyFrom = self::DB_ID_FIELD;
            $keyTo = self::ID_FIELD;
        }

        if (!isset($data[$keyFrom])) {
            return false;
        }

        $data[$keyTo] = $data[$keyFrom];
        unset($data[$keyFrom]);
        return $data;
    }

    ...
