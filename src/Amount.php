<?php

declare(strict_types = 1);

namespace QuetzalStudio\SnapBi;

use Illuminate\Contracts\Support\Arrayable;

class Amount implements Arrayable
{
    public function __construct(
        public float $value,
        public string $currency = 'IDR',
    ) {}

    public function toArray()
    {
        return [
            'value' => number_format($this->value, 2, '.', ''),
            'currency' => $this->currency,
        ];
    }
}
