<?php

namespace App\View\Color;

use App\Entity\Color;

class View
{
    public int $id;
    public string $name;
    public string $hex;

    public function __construct(Color $color)
    {
        $this->id = $color->getId();
        $this->name = $color->getName();
        $this->hex = $color->getHex();
    }
}