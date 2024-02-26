<?php

declare(strict_types=1);

namespace Modules\Subscription\App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserPlan extends Enum
{
    public const Normal = 1;
    public const Premium = 2;
    public const Expired = 3;
}
