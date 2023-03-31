<?php

declare(strict_types=1);

namespace Phpolar\Validators;

use Phpolar\Validators\Tests\DataProviders\MinLengthDataProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionAttribute;
use ReflectionProperty;

#[CoversClass(MinLength::class)]
final class MinLengthTest extends TestCase
{
    #[Test]
    #[DataProviderExternal(MinLengthDataProvider::class, "strAboveMin")]
    public function shallBeValidIfPropIsGteMinLen(string $valAboveMin)
    {
        $obj = new class($valAboveMin)
        {
            #[MinLength(MinLengthDataProvider::MIN_LEN)]
            public string $property;

            public function __construct(string $prop)
            {
                $this->property = $prop;
            }
        };

        /**
         * @var MinLength[] $suts
         */
        $suts = $this->getSuts($obj);
        $this->assertNotEmpty($suts);
        foreach ($suts as $sut) {
            $this->assertTrue($sut->isValid());
            $this->assertEmpty($sut->getMessages());
        }
    }

    #[Test]
    #[DataProviderExternal(MinLengthDataProvider::class, "strBelowMin")]
    public function shallBeInvalidIfPropIsLtMinLen(string $valBelowMin)
    {
        $obj = new class($valBelowMin)
        {
            #[MinLength(MinLengthDataProvider::MIN_LEN)]
            public string $property;

            public function __construct(string $prop)
            {
                $this->property = $prop;
            }
        };

        /**
         * @var MinLength[] $suts
         */
        $suts = $this->getSuts($obj);
        $this->assertNotEmpty($suts);
        foreach ($suts as $sut) {
            $this->assertFalse($sut->isValid());
            $this->assertNotEmpty($sut->getMessages());
        }
    }

    #[Test]
    #[DataProviderExternal(MinLengthDataProvider::class, "numberAboveMin")]
    public function shallBeValidIfNumericPropIsGteMinLen(int|float $valAboveMin)
    {
        $obj = new class($valAboveMin)
        {
            #[MinLength(MinLengthDataProvider::MIN_LEN)]
            public int|float $property;

            public function __construct(int|float $prop)
            {
                $this->property = $prop;
            }
        };

        /**
         * @var MinLength[] $suts
         */
        $suts = $this->getSuts($obj);
        $this->assertNotEmpty($suts);
        foreach ($suts as $sut) {
            $this->assertTrue($sut->isValid());
            $this->assertEmpty($sut->getMessages());
        }
    }

    #[Test]
    #[DataProviderExternal(MinLengthDataProvider::class, "numberBelowMin")]
    public function shallBeInvalidIfNumericPropIsLtMinLen(int|float $valBelowMin)
    {
        $obj = new class($valBelowMin)
        {
            #[MinLength(MinLengthDataProvider::MIN_LEN)]
            public int|float $property;

            public function __construct(int|float $prop)
            {
                $this->property = $prop;
            }
        };

        /**
         * @var MinLength[] $suts
         */
        $suts = $this->getSuts($obj);
        $this->assertNotEmpty($suts);
        foreach ($suts as $sut) {
            $this->assertFalse($sut->isValid());
            $this->assertNotEmpty($sut->getMessages());
        }
    }


    #[TestDox("Shall be valid if property type does not have a length")]
    public function testA()
    {
        $obj = new class(null)
        {
            #[MinLength(MinLengthDataProvider::MIN_LEN)]
            public mixed $property;

            public function __construct(mixed $prop)
            {
                $this->property = $prop;
            }
        };

        /**
         * @var MinLength[] $suts
         */
        $suts = $this->getSuts($obj);
        $this->assertNotEmpty($suts);
        foreach ($suts as $sut) {
            $this->assertTrue($sut->isValid());
            $this->assertEmpty($sut->getMessages());
        }
    }

    private function getSuts(object $obj): array
    {
        $prop = new ReflectionProperty($obj, "property");
        $getAttrs = static function (ReflectionAttribute $attr) use ($prop, $obj) {
            $instance = $attr->newInstance();
            $instance->propVal = $prop->isInitialized($obj) === true ? $prop->getValue($obj) : $prop->getDefaultValue();
            return $instance;
        };
        return array_map($getAttrs, $prop->getAttributes(MinLength::class));
    }
}
