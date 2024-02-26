<?php

declare(strict_types=1);

namespace Modules\User\App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserStatus extends Enum
{
    public const Registered = 1;
    public const PasswordReset = 2;
}
