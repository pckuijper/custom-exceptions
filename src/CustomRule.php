<?php

declare(strict_types = 1);

namespace Acme;

use Qossmic\Deptrac\Contract\Analyser\ProcessEvent;
use Qossmic\Deptrac\Contract\Ast\DependencyType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CustomRule implements EventSubscriberInterface
{
    const EXCEPTIONS = [
        "RuntimeException",
        "Exception",
        # ...
        ];

    public function onProcessEvent(ProcessEvent $event)
    {
        $dependency = $event->dependency;
        if ($dependency->getType() !== DependencyType::INHERIT) {
            return;
        }
        if(in_array($dependency->getDependent()->toString(), self::EXCEPTIONS, true)) {
            $event->stopPropagation();
        }

    }

    public static function getSubscribedEvents()
    {
        return [
            ProcessEvent::class => 'onProcessEvent'
        ];
    }
}
