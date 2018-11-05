<?php

namespace App\Presenters;
use DateTime;

class DateTimePresenter
{
    public function consistFormat($item, $attributes)
    {
        foreach($attributes as $attribute) {
            $item->$attribute = $this->format($item->$attribute);
        }
        return $item;
    }

    private function format($original)
    {
        $datetime = new DateTime($original);
        return $datetime->format('Y-m-d\TH:i:s');
    }
}
