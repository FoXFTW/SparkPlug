<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 06.12.2017
 * Time: 12:17
 */

namespace App\Tests;

use App\SparkPlug\Validation\Exceptions\ValidationException;
use App\SparkPlug\Validation\Validation;

/**
 * Class ValidationClassTest
 * @package App\Tests
 * @covers  \App\SparkPlug\Validation\Validation
 */
class ValidationClassTest extends TestCase
{
    /** @var \App\SparkPlug\Validation\Validation */
    private $validator;

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testBoolean()
     */
    public function testBoolean()
    {
        $result = $this->validator->validate(
            [
                'bool'             => 'boolean',
                'bool_true_string' => 'boolean',
                'bool_yes'         => 'boolean',
                'bool_1'           => 'boolean',
                'bool_1_string'    => 'boolean',

                'bool_false'          => 'boolean',
                'bool_false_string'   => 'boolean',
                'bool_false_no'       => 'boolean',
                'bool_false_0'        => 'boolean',
                'bool_false_0_string' => 'boolean',
            ],
            [
                'bool'             => true,
                'bool_true_string' => 'true',
                'bool_yes'         => 'yes',
                'bool_1'           => 1,
                'bool_1_string'    => '1',

                'bool_false'          => false,
                'bool_false_string'   => 'false',
                'bool_false_no'       => 'no',
                'bool_false_0'        => 0,
                'bool_false_0_string' => '0',
            ]
        );

        $this->assertArrayHasKey('bool', $result);
    }


    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testBoolean()
     */
    public function testBooleanFail()
    {
        $this->expectException(ValidationException::class);
        $this->validator->validate(
            [
                'bool' => 'boolean',
            ],
            [
                'bool' => 'keinBool',
            ]
        );
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testEmail()
     */
    public function testEmail()
    {
        $result = $this->validator->validate(
            [
                'email' => 'email',
            ],
            [
                'email' => 'test@example.com',
            ]
        );
        $this->assertArrayHasKey('email', $result);
    }


    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testEmail()
     */
    public function testEmailFail()
    {
        $this->expectException(ValidationException::class);
        $this->validator->validate(
            [
                'email'             => 'email',
                'email_ohne_domain' => 'email',
            ],
            [
                'email'             => 'nomail',
                'email_ohne_domain' => 'test@example',
            ]
        );
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testInt()
     */
    public function testInt()
    {
        $result = $this->validator->validate(
            [
                'int'        => 'int',
                'int_string' => 'int',
            ],
            [
                'int'        => 1,
                'int_string' => '12',
            ]
        );
        $this->assertArrayHasKey('int', $result);
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testInt()
     */
    public function testIntFail()
    {
        $this->expectException(ValidationException::class);
        $this->validator->validate(
            [
                'int' => 'int',
            ],
            [
                'int' => 12.5,
            ]
        );
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testFloat()
     */
    public function testFloat()
    {
        $result = $this->validator->validate(
            [
                'float'        => 'float',
                'float_string' => 'float',
            ],
            [
                'float'        => 1.0,
                'float_string' => '12.5',
            ]
        );
        $this->assertArrayHasKey('float', $result);
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testFloat()
     */
    public function testFloatFail()
    {
        $this->expectException(ValidationException::class);
        $this->validator->validate(
            [
                'float' => 'float',
            ],
            [
                'float' => 'a12',
            ]
        );
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testUrl()
     */
    public function testUrl()
    {
        $result = $this->validator->validate(
            [
                'url' => 'url',
            ],
            [
                'url' => 'http://example.com',
            ]
        );
        $this->assertArrayHasKey('url', $result);
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testFloat()
     */
    public function testUrlFail()
    {
        $this->expectException(ValidationException::class);
        $this->validator->validate(
            [
                'url' => 'url',
            ],
            [
                'url' => 'example.com',
            ]
        );
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testUsername()
     */
    public function testUsername()
    {
        $result = $this->validator->validate(
            [
                'username'  => 'username',
                'username1' => 'username',
                'username2' => 'username',
                'username3' => 'username',
                'username4' => 'username',
                'username5' => 'username',
            ],
            [
                'username'  => 'UserName',
                'username1' => 'User_Name',
                'username2' => 'User-Name',
                'username3' => '1UserName2',
                'username4' => '1User-_-Name',
                'username5' => '123',
            ]
        );
        $this->assertArrayHasKey('username', $result);
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testUsername()
     */
    public function testUsernameFail()
    {
        $this->expectException(ValidationException::class);
        $this->validator->validate(
            [
                'username' => 'username',
            ],
            [
                'username' => '#124<>',
            ]
        );
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testMin()
     * @covers \App\SparkPlug\Validation\Validation::testString()
     */
    public function testMinString()
    {
        $result = $this->validator->validate(
            [
                'string' => 'string|min:3',
            ],
            [
                'string' => 'abc',
            ]
        );
        $this->assertArrayHasKey('string', $result);
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testMin()
     * @covers \App\SparkPlug\Validation\Validation::testString()
     */
    public function testMinStringFail()
    {
        $this->expectException(ValidationException::class);
        $this->validator->validate(
            [
                'string' => 'string|min:3',
            ],
            [
                'string' => 'ab',
            ]
        );
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testMin()
     * @covers \App\SparkPlug\Validation\Validation::testMax()
     * @covers \App\SparkPlug\Validation\Validation::testString()
     */
    public function testMinMaxString()
    {
        $result = $this->validator->validate(
            [
                'string' => 'string|min:3|max:3',
            ],
            [
                'string' => 'abc',
            ]
        );
        $this->assertArrayHasKey('string', $result);
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testMin()
     * @covers \App\SparkPlug\Validation\Validation::testMax()
     * @covers \App\SparkPlug\Validation\Validation::testString()
     */
    public function testMinMaxStringFail()
    {
        $this->expectException(ValidationException::class);
        $this->validator->validate(
            [
                'string' => 'string|min:3|max:3',
            ],
            [
                'string' => 'ab',
            ]
        );
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testMin()
     */
    public function testMinNumeric()
    {
        $result = $this->validator->validate(
            [
                'int' => 'int|min:3',
            ],
            [
                'int' => 4,
            ]
        );
        $this->assertArrayHasKey('int', $result);
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testMin()
     */
    public function testMinNumericFail()
    {
        $this->expectException(ValidationException::class);
        $this->validator->validate(
            [
                'int' => 'int|min:3',
            ],
            [
                'int' => 2,
            ]
        );
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testMax()
     */
    public function testMinMaxNumeric()
    {
        $result = $this->validator->validate(
            [
                'int' => 'int|min:3|max:3',
            ],
            [
                'int' => 3,
            ]
        );
        $this->assertArrayHasKey('int', $result);
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testMin()
     * @covers \App\SparkPlug\Validation\Validation::testMax()
     */
    public function testMinMaxNumericFail()
    {
        $this->expectException(ValidationException::class);
        $this->validator->validate(
            [
                'int' => 'int|min:3|max:4',
            ],
            [
                'int' => 2,
            ]
        );
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testMax()
     * @covers \App\SparkPlug\Validation\Validation::testString()
     */
    public function testMaxString()
    {
        $result = $this->validator->validate(
            [
                'string' => 'string|max:3',
            ],
            [
                'string' => 'abc',
            ]
        );
        $this->assertArrayHasKey('string', $result);
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testMax()
     * @covers \App\SparkPlug\Validation\Validation::testString()
     */
    public function testMaxStringFail()
    {
        $this->expectException(ValidationException::class);
        $this->validator->validate(
            [
                'string' => 'string|max:3',
            ],
            [
                'string' => 'abcdefg',
            ]
        );
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testMax()
     */
    public function testMaxNumeric()
    {
        $result = $this->validator->validate(
            [
                'int' => 'int|max:10000000',
            ],
            [
                'int' => '999',
            ]
        );
        $this->assertArrayHasKey('int', $result);
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testMax()
     */
    public function testMaxNumericFail()
    {
        $this->expectException(ValidationException::class);
        $this->validator->validate(
            [
                'int' => 'int|max:1',
            ],
            [
                'int' => 2,
            ]
        );
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testConfirmed()
     */
    public function testConfirmed()
    {
        $result = $this->validator->validate(
            [
                'int' => 'string|confirmed',
            ],
            [
                'int' => '999',
                'int_confirmation' => '999',
            ]
        );
        $this->assertArrayHasKey('int', $result);
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testConfirmed()
     */
    public function testConfirmedFail()
    {
        $this->expectException(ValidationException::class);
        $this->validator->validate(
            [
                'int' => 'int|confirmed',
            ],
            [
                'int' => 2,
            ]
        );
    }

    /**
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @covers \App\SparkPlug\Validation\Validation::testConfirmed()
     */
    public function testConfirmedFailUnequal()
    {
        $this->expectException(ValidationException::class);
        $this->validator->validate(
            [
                'int' => 'int|confirmed',
            ],
            [
                'int' => 2,
                'int_confirmation' => 3,
            ]
        );
    }

    protected function setUp()
    {
        parent::setUp();
        $this->validator = new Validation();
    }
}
