<?php

namespace QuetzalStudio\SnapBi;

class Timestamp
{
    public function __construct(
        protected string|int $timestamp
    ) {}

    public function __toString()
    {
        return preg_match('/^\d{10}+/', $this->timestamp) ? date(DATE_ATOM, $this->timestamp) :  $this->timestamp;
    }
}
