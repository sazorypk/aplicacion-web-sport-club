<?php

use Illuminate\Contracts\Debug\ExceptionHandler;
use Sentry\SentrySdk;
use Sentry\State\HubInterface;

test('reportable exceptions are sent to the Sentry integration', function () {
    $exception = new RuntimeException('Error de prueba de Sport Club');
    $originalHub = SentrySdk::getCurrentHub();
    $hub = Mockery::mock(HubInterface::class);
    $hub->shouldReceive('captureException')->once()->with($exception, Mockery::any())->andReturnNull();

    try {
        SentrySdk::setCurrentHub($hub);
        app(ExceptionHandler::class)->report($exception);
    } finally {
        SentrySdk::setCurrentHub($originalHub);
    }
});
