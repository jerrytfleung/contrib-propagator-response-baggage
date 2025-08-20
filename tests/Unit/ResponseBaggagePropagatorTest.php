<?php

declare(strict_types=1);

namespace OpenTelemetry\Tests\Propagation\ResponseBaggage\Unit;

use OpenTelemetry\Context\Context;
use OpenTelemetry\Contrib\Propagation\ResponseBaggage\ResponseBaggage;
use OpenTelemetry\Contrib\Propagation\ResponseBaggage\ResponseBaggagePropagator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ResponseBaggagePropagator::class)]
final class ResponseBaggagePropagatorTest extends TestCase
{
    public function test_fields(): void
    {
        $propagator = new ResponseBaggagePropagator();
        $this->assertSame([], $propagator->fields());
    }
    public function test_inject_empty(): void
    {
        $carrier = [];
        (new ResponseBaggagePropagator())->inject($carrier);
        $this->assertEquals([], $carrier);
    }
    public function test_inject(): void
    {
        $carrier = [];
        (new ResponseBaggagePropagator())->inject($carrier, null, Context::getCurrent()->withContextValue(ResponseBaggage::getBuilder()->set('k1', '1')->set('k2', 2)->build()));
        $this->assertEquals(['k1'=>'1', 'k2'=>'2'], $carrier);
    }
    public function test_inject_current_context(): void
    {
        $carrier = [];
        $scope = ResponseBaggage::getBuilder()->set('k1', '1')->set('k2', 2)->build()->activate();
        (new ResponseBaggagePropagator())->inject($carrier);
        $scope->detach();
        $this->assertEquals(['k1'=>'1', 'k2'=>'2'], $carrier);
    }
}
