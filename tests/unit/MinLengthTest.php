<?php

declare(strict_types=1);

namespace Phpolar\Validation;

use Phpolar\Validation\Tests\DataProviders\MinLengthDataProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
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
        $obj = new class ($valAboveMin)
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
        $foundSut = false;

        foreach ($suts as $sut) {
            $foundSut = true;
            $this->assertTrue($sut->isValid());
        }
        $this->assertTrue($foundSut);

    }

    #[Test]
    #[DataProviderExternal(MinLengthDataProvider::class, "strBelowMin")]
    public function shallBeInvalidIfPropIsLtMinLen(string $valBelowMin)
    {
        $obj = new class ($valBelowMin)
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
        $foundSut = false;

        foreach ($suts as $sut) {
            $foundSut = true;
            $this->assertFalse($sut->isValid());
        }
        $this->assertTrue($foundSut);
    }

    #[Test]
    #[DataProviderExternal(MinLengthDataProvider::class, "numberAboveMin")]
    public function shallBeValidIfNumericPropIsGteMinLen(int|float $valAboveMin)
    {
        $obj = new class ($valAboveMin)
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
        $foundSut = false;

        foreach ($suts as $sut) {
            $foundSut = true;
            $this->assertTrue($sut->isValid());
        }
        $this->assertTrue($foundSut);
    }

    #[Test]
    #[DataProviderExternal(MinLengthDataProvider::class, "numberBelowMin")]
    public function shallBeInvalidIfNumericPropIsLtMinLen(int|float $valBelowMin)
    {
        $obj = new class ($valBelowMin)
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
        $foundSut = false;

        foreach ($suts as $sut) {
            $foundSut = true;
            $this->assertFalse($sut->isValid());
        }
        $this->assertTrue($foundSut);
    }


    #[TestDox("Shall be valid if property type does not have a length")]
    public function testA()
    {
        $obj = new class (null)
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
        $foundSut = false;

        foreach ($suts as $sut) {
            $foundSut = true;
            $this->assertTrue($sut->isValid());
        }
        $this->assertTrue($foundSut);
    }

    private function getSuts(object $obj): array
    {
        $prop = new ReflectionProperty($obj, "property");
        return array_map(fn (ReflectionAttribute $attr) => $attr->newInstance()->withPropVal($prop, $obj), $prop->getAttributes(MinLength::class));
    }
}
