<?php

use Orchestra\Testbench\TestCase;
use LittleApps\SerializableModel\Serializable;

class SerializableTest extends TestCase {
	/**
     * Setup the test environment.
     */
    public function setUp() {
        parent::setUp();
        
		$this->loadLaravelMigrations(['--database' => 'testing']);
    }
	
	/**
	 * Tear down the test environment.
	 */
	public function tearDown() {
		parent::tearDown();
		
        Option::truncate();
    }
	
	/**
     * Define environment setup.
     *
     * @param  Illuminate\Foundation\Application    $app
     * @return void
     */
    protected function getEnvironmentSetUp($app) {
        $app['config']->set('database.default', 'testing');
	}
	
	public function testUsesSerializable() {
		$option = new Option;
		
		$this->assertContains(Serializable::class, class_uses($option));
	}
}