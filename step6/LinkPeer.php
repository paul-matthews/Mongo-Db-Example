<?php

    ...

    public function insert(Link $link)
    {
        return $this->update($link);
    }

    public function update(Link $link)
    {
        $data = $this->translateDataset($link->toArray(), true);

        if ($data && $this->getCollection()->save($data)) {
            return true;
        }

        return false;
    }

    ...
