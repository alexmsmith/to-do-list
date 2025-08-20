<?php

namespace App\Enums;

enum StatusEnum: int
{
    case Active = 1;
    case Complete = 2;
    case Deleted = 3;
}