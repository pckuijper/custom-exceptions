<?php

declare(strict_types=1);

namespace Acme\Module;

use RuntimeException;

final class BarService
{
    public function bar(): void
    {
        throw new RuntimeException();
    }
}
