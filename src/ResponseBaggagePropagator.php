<?php

declare(strict_types=1);

namespace OpenTelemetry\Contrib\Propagation\ResponseBaggage;

use OpenTelemetry\Context\Context;
use OpenTelemetry\Context\ContextInterface;
use OpenTelemetry\Context\Propagation\ArrayAccessGetterSetter;
use OpenTelemetry\Context\Propagation\PropagationSetterInterface;
use Override;

/**
 * Provides a ResponsePropagator implementation for Response Baggage.
 */
final class ResponseBaggagePropagator implements ResponsePropagator
{
    public function fields(): array
    {
        return [];
    }
    #[Override]
    public function inject(&$carrier, ?PropagationSetterInterface $setter = null, ?ContextInterface $context = null): void
    {
        $setter ??= ArrayAccessGetterSetter::getInstance();
        $context ??= Context::getCurrent();
        $responseBaggage = ResponseBaggage::fromContext($context);
        if($responseBaggage->isEmpty()) {
            return;
        }
        foreach ($responseBaggage->getAll() as $key => $value) {
            $setter->set($carrier, $key, (string)$value->getValue());
        }
    }
}
