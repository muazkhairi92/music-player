<?php

declare(strict_types=1);

namespace Modules\Subscription\App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserTransactionType extends Enum
{
    public const Pending = 'pending';

    public const Success = 'success';

    public const Fail = 'fail';
}
