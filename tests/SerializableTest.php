<?php

use Orchestra\Testbench\TestCase;
use LittleApps\SerializableModel\Serializable;
use Faker\Factory as FakerFactory;

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
		
    }
	
	/**
     * Define environment setup.
     *
     * @param  Illuminate\Foundation\Application    $app
     * @return void
     */
    protected function getEnvironmentSetUp($app) {
        $app['config']->set('database.default', 'testing');
        
        $app->setBasePath(__DIR__);
	}
	
	protected function getOptionName() {
		return 'serializable';
	}
	
	protected function getRawOptionValue($name) {
		$option = DB::table('options')->where(compact('name'))->first();
		
		return $option->value;
	}
	
	protected function getFaker() {
		return FakerFactory::create();
	}
	
	public function testUsesSerializable() {
		$option = new Option;
		
		$this->assertContains(Serializable::class, class_uses($option));
	}
	
	/**
	 * Test a string is stored as is
	 */
	public function testStoresString() {
		$expected = $this->getFaker()->text(30);
		
		$option = Option::create(['name' => $this->getOptionName(), 'value' => $expected]);
		
		$this->assertEquals($expected, $option->value);
		$this->assertEquals($expected, $this->getRawOptionValue($this->getOptionName()));
	}
	
}