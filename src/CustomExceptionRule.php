<?php

declare(strict_types = 1);

namespace Acme;

use Exception;
use Qossmic\Deptrac\Contract\Analyser\ProcessEvent;
use RuntimeException;

final class CustomExceptionRule
{
    private const EXCEPTIONS = [
        RuntimeException::class,
        Exception::class,
        # ...
    ];

    private const ALLOWED_DEPENDENDS = [
        self::class
    ];

    public function __invoke(ProcessEvent $event)
    {
        $dependency = $event->getDependency();

        if ($this->isDependerAllowed($dependency->getDepender()->toString())) {
            $event->stopPropagation();
        }

        if (strpos($dependency->getDepender()->toString(), 'Exception') === false) {
            return;
        }

        if(in_array($dependency->getDependent()->toString(), self::EXCEPTIONS, true)) {
            $event->stopPropagation();
        }
    }

    private function isDependerAllowed(string $depender): bool
    {
        return in_array($depender, self::ALLOWED_DEPENDENDS, true);
    }
}
