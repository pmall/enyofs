<?php

namespace App;

use Illuminate\Support\Collection;

class FilesCollection extends Collection
{
    public function sortByType(Array $order = ['dir', 'file'])
    {
        $order = array_flip($order);

        return $this->sort(function ($a, $b) use ($order) {

            return $order[$a['type']] - $order[$b['type']];

        });
    }
}
