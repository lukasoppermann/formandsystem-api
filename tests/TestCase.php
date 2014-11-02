<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	/**
	 * Creates the application.
	 *
	 * @return \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;

		$testEnvironment = 'testing';

		return require __DIR__.'/../bootstrap/start.php';

    if ( ! $app->hasBeenBootstrapped())
    {
        $app->bootstrapWith(
            [
                'Illuminate\Foundation\Bootstrap\DetectEnvironment',
                'Illuminate\Foundation\Bootstrap\LoadConfiguration',
                'Illuminate\Foundation\Bootstrap\RegisterFacades',
                'Illuminate\Foundation\Bootstrap\SetRequestForConsole',
                'Illuminate\Foundation\Bootstrap\RegisterProviders',
                'Illuminate\Foundation\Bootstrap\BootProviders',
            ]
        );
    }

    return $app;
	}

}
