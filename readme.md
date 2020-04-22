# Laravel Swagger Test

> Underlaying logic uses [PHP Swagger Test](https://github.com/byjg/php-swagger-test) from [byjg](https://github.com/byjg)

Test your routes using Laravel's underlying request testing (without making real request) against your API schema.

## Support

> How to make tests and which OpenAPI is supported check the [PHP Swagger Test](https://github.com/byjg/php-swagger-test).
 
 At the time of writing this readme OpenAPI 3 is partially supported.
 
 ## Usage
 
 Use the Laravel's TestCase and use `AssertRequestAgainstSchema` trait assert request against schema.
 
 Uses same "request building" as `ApiRequester`. For more details check the [PHP Swagger Test](https://github.com/byjg/php-swagger-test).
 
 ```php
use Tests\TestCase;
use ByJG\ApiTools\AssertRequestAgainstSchema;
use ByJG\ApiTools\Base\Schema;
use ByJG\ApiTools\Laravel\LaravelRequester;

class GetUsersTest extends TestCase
{
    use AssertRequestAgainstSchema;
    
    /**
     * Loaded schema for phpunit instance.
     *
     * @var Schema|null
     */
    public static $cachedSchema = null;

    protected function setUp()
    {
        parent::setUp();

        // Load only once, must be made in setup to be able to use base_path
        if (null !== $this->schema) {
            return;
        }

        // Load only once per phpunit instance
        if (null === self::$cachedSchema) {
            self::$cachedSchema = Schema::getInstance(file_get_contents(base_path('docs/api.json')));
        }

        // Set the schema
        $this->setSchema(self::$cachedSchema);
    }

    public function testGetUsersWithoutFiltersInElasticSearchAgainstSchema()
    {
        // Create data
        $this->createUser();

        $request = new LaravelRequester($this);
        $request
            ->withMethod('GET')
            ->withPath('/v1/users');

        $this->assertRequest($request);
    }
}
 
``` 
