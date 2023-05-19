<?php

declare(strict_types = 1);

namespace Acme;

use Exception;
use Qossmic\Deptrac\Contract\Analyser\ProcessEvent;
use Qossmic\Deptrac\Contract\Ast\DependencyType;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class CustomExceptionRule implements EventSubscriberInterface
{
    private const EXCEPTIONS = [
        RuntimeException::class,
        Exception::class,
        # ...
    ];

    private const ALLOWED_DEPENDENDS = [
        self::class
    ];

    public static function getSubscribedEvents()
    {
        return [
            ProcessEvent::class => 'onProcessEvent'
        ];
    }

    public function onProcessEvent(ProcessEvent $event)
    {
        $dependency = $event->dependency;

        if ($this->isDependerAllowed($dependency->getDepender()->toString())) {
            $event->stopPropagation();
        }

        if ($dependency->getType() !== DependencyType::INHERIT) {
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
