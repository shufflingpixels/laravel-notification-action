<?php

namespace ShufflingPixels\NotificationAction\Attributes;

use Attribute;

#[Attribute]
class Action
{
    public function __construct(public string $class)
    {
    }
}
