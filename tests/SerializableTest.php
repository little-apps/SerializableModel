<?php

use Orchestra\Testbench\TestCase;
use LittleApps\SerializableModel\Serializable;
use Illuminate\Foundation\Testing\WithFaker;

class SerializableTest extends TestCase {
	use WithFaker;
	
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
	
	public function testUsesSerializable() {
		$option = new Option;
		
		$this->assertContains(Serializable::class, class_uses($option));
	}
	
	/**
	 * Test a string is stored as is
	 * 
	 * @return void
	 */
	public function testStoresString() {
		$expected = $this->faker->text(30);
		
		$option = Option::create(['name' => $this->getOptionName(), 'value' => $expected]);
		
		$actualOption = Option::where('name', $this->getOptionName())->first();
		
		$this->assertNotNull($actualOption);
		$this->assertEquals($expected, $actualOption->value);
		$this->assertEquals($expected, $this->getRawOptionValue($this->getOptionName()));
	}
	
	/**
	* Tests an int is stored serialized.
	* 
	* @return void
	*/
	public function testStoresNumber() {
		$expectedNumber = $this->faker->randomNumber;
		$expectedNumberSerialized = serialize($expectedNumber);
		
		$option = Option::create(['name' => $this->getOptionName(), 'value' => $expectedNumber]);
		
		$actualOption = Option::where('name', $this->getOptionName())->first();
		
		$this->assertNotNull($actualOption);
		$this->assertEquals($expectedNumber, $actualOption->value);
		$this->assertEquals($expectedNumberSerialized, $this->getRawOptionValue($this->getOptionName()));
	}
	
	/**
	* Tests a float is stored serialized.
	* 
	* @return void
	*/
	public function testStoresFloat() {
		$expectedFloat = $this->faker->randomFloat;
		$expectedFloatSerialized = serialize($expectedFloat);
		
		$option = Option::create(['name' => $this->getOptionName(), 'value' => $expectedFloat]);
		
		$actualOption = Option::where('name', $this->getOptionName())->first();
		
		$this->assertNotNull($actualOption);
		$this->assertEquals($expectedFloat, $actualOption->value);
		$this->assertEquals($expectedFloatSerialized, $this->getRawOptionValue($this->getOptionName()));
	}
	
	/**
	* Tests a array is stored serialized.
	* 
	* @return void
	*/
	public function testStoresArray() {
		$expectedArray = $this->faker->randomElements(['John', 'Peter', 'Mark', 'Paul', 'Steven', 'Mary'], 3);
		$expectedArraySerialized = serialize($expectedArray);
		
		$option = Option::create(['name' => $this->getOptionName(), 'value' => $expectedArray]);
		
		$actualOption = Option::where('name', $this->getOptionName())->first();
		
		$this->assertNotNull($actualOption);
		$this->assertEquals($expectedArray, $actualOption->value);
		$this->assertEquals($expectedArraySerialized, $this->getRawOptionValue($this->getOptionName()));
	}
	
	/**
	* Tests a boolean is stored serialized.
	* 
	* @return void
	*/
	public function testStoresBoolean() {
		$expectedBoolean = $this->faker->boolean;
		$expectedBooleanSerialized = serialize($expectedBoolean);
		
		$option = Option::create(['name' => $this->getOptionName(), 'value' => $expectedBoolean]);
		
		$actualOption = Option::where('name', $this->getOptionName())->first();
		
		$this->assertNotNull($actualOption);
		$this->assertEquals($expectedBoolean, $actualOption->value);
		$this->assertEquals($expectedBooleanSerialized, $this->getRawOptionValue($this->getOptionName()));
	}
	
	/**
	* Tests a null is stored serialized.
	* 
	* @return void
	*/
	public function testStoresNull() {
		$expectedNull = null;
		$expectedNullSerialized = serialize($expectedNull);
		
		$option = Option::create(['name' => $this->getOptionName(), 'value' => $expectedNull]);
		
		$actualOption = Option::where('name', $this->getOptionName())->first();
		
		$this->assertNotNull($actualOption);
		$this->assertNull($actualOption->value);
		$this->assertEquals($expectedNullSerialized, $this->getRawOptionValue($this->getOptionName()));
	}
	
	/**
	* Tests a object is stored serialized.
	* 
	* @return void
	*/
	public function testStoresObject() {
		$expectedObject = new Option(['name' => $this->faker->word, 'value' => $this->faker->sentence]);
		$expectedObjectSerialized = serialize($expectedObject);
		
		$option = Option::create(['name' => $this->getOptionName(), 'value' => $expectedObject]);
		
		$actualOption = Option::where('name', $this->getOptionName())->first();
		
		$this->assertNotNull($actualOption);
		$this->assertInstanceOf(Option::class, $actualOption->value);
		$this->assertEquals($expectedObject, $actualOption->value);
		$this->assertEquals((string) $expectedObject, (string) $actualOption->value);
		$this->assertEquals($expectedObjectSerialized, $this->getRawOptionValue($this->getOptionName()));
	}
	
	/**
	* Tests a resource is not stored.
	* 
	* @return void
	*/
	public function testCantStoreResource() {
		$expectedResource = @fopen('php://temp', 'w');
		
		$this->assertInternalType('resource', $expectedResource);
		
		$option = Option::create(['name' => $this->getOptionName(), 'value' => $expectedResource]);
		
		$actualOption = Option::where('name', $this->getOptionName())->first();
		
		$this->assertNotNull($actualOption);
		$this->assertNotInternalType('resource', $actualOption->value);
		$this->assertNotEquals($expectedResource, $actualOption->value);
		
		fclose($expectedResource);
	}
	
}