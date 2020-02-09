<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;
use App\Models\{User, Author};

final class EntityType extends Enum implements LocalizedEnum
{
    const user = User::class;
    const author = Author::class;
}
