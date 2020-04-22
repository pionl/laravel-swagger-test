# Laravel Swagger Test

> Underlying logic uses [PHP Swagger Test](https://github.com/byjg/php-swagger-test) from [byjg](https://github.com/byjg)

Test your routes using Laravel's underlying request testing (without making real request) against your API schema.

## Support

> How to make tests and which OpenAPI is supported check the [PHP Swagger Test](https://github.com/byjg/php-swagger-test).
 
 At the time of writing this readme OpenAPI 3 is partially supported.
 
 ## Install
 
 1. Add a custom repository for php-swagger-test with internal improvements. (In future it could be merged).
 
    ```
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/pionl/php-swagger-test"
        }
    ]
    ```
 2. Require the package
    
    ```
    compsoer require pion/laravel-swagger-test
    ```
 
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
