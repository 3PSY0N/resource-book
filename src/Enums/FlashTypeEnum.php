<?php

namespace App\Enums;

enum FlashTypeEnum: string
{
    case SUCCESS = 'success';
    case INFO = 'info';
    case WARNING = 'warning';
    case ERROR = 'error';
}
