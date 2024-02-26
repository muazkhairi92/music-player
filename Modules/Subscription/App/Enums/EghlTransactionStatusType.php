<?php

declare(strict_types=1);

namespace Modules\Subscription\App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class EghlTransactionStatusType extends Enum
{
    public const Pending = 2;

    public const Success = 0;

    public const Fail = 1;
}
