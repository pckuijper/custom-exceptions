<?php

declare(strict_types=1);

namespace Acme\Module;

final class FooService
{
    public function foo(): void
    {
        throw new SpecificException();
    }
}
