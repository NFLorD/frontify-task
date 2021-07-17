<?php

namespace App\View\Color;

use App\Entity\Color;

class ListView
{
    public int $page;
    public int $count;

    /** @var array<View> */
    public array $items;

    /** @param array<Color> $colors */
    public function __construct(int $page, array $colors)
    {
        $this->page = $page;
        $this->count = count($colors);

        $this->items = array_map(
            fn (Color $color) => new View($color),
            $colors
        );
    }
}