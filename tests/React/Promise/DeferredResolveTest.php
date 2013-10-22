<?php

namespace React\Promise;

/**
 * @group Deferred
 * @group DeferredResolve
 */
class DeferredResolveTest extends TestCase
{
    /** @test */
    public function shouldResolve()
    {
        $d = new Deferred();

        $mock = $this->createCallableMock();
        $mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->identicalTo(1));

        $d
            ->promise()
            ->then($mock);

        $d
            ->resolve(1);
    }

    /** @test */
    public function shouldResolveWithPromisedValue()
    {
        $d = new Deferred();

        $mock = $this->createCallableMock();
        $mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->identicalTo(1));

        $d
            ->promise()
            ->then($mock);

        $d
            ->resolve(new FulfilledPromise(1));
    }

    /** @test */
    public function shouldRejectWhenResolvedWithRejectedPromise()
    {
        $d = new Deferred();

        $mock = $this->createCallableMock();
        $mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->identicalTo(1));

        $d
            ->promise()
            ->then($this->expectCallableNever(), $mock);

        $d
            ->resolve(new RejectedPromise(1));
    }

    /** @test */
    public function shouldReturnAPromiseForTheResolutionValue()
    {
        $d = new Deferred();

        $mock = $this->createCallableMock();
        $mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->identicalTo(1));

        $d
            ->resolve(1)
            ->then($mock);
    }

    /** @test */
    public function shouldReturnAPromiseForAPromisedResolutionValue()
    {
        $d = new Deferred();

        $mock = $this->createCallableMock();
        $mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->identicalTo(1));

        $d
            ->resolve(resolve(1))
            ->then($mock);
    }

    /** @test */
    public function shouldReturnAPromiseForAPromisedRejectionValue()
    {
        $d = new Deferred();

        $mock = $this->createCallableMock();
        $mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->identicalTo(1));

        // Both the returned promise, and the deferred's own promise should
        // be rejected with the same value
        $d
            ->resolve(reject(1))
            ->then($this->expectCallableNever(), $mock);
    }

    /** @test */
    public function shouldInvokeNewlyAddedCallbackWhenAlreadyResolved()
    {
        $d = new Deferred();
        $d
            ->resolve(1);

        $mock = $this->createCallableMock();
        $mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->identicalTo(1));

        $d
            ->promise()
            ->then($mock, $this->expectCallableNever());
    }

    /** @test */
    public function shouldForwardValueWhenCallbackIsNull()
    {
        $mock = $this->createCallableMock();
        $mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->identicalTo(1));

        $d = new Deferred();
        $d
            ->promise()
            ->then(
                null,
                $this->expectCallableNever()
            )
            ->then(
                $mock,
                $this->expectCallableNever()
            );

        $d->resolve(1);
    }
}
