<?php

declare(strict_types=1);

namespace Phpolar\Validation;

use Phpolar\Validation\Tests\DataProviders\MaxLengthDataProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use ReflectionAttribute;
use ReflectionProperty;

#[CoversClass(MaxLength::class)]
#[CoversClass(AbstractPropertyValueExtractor::class)]
final class MaxLengthTest extends TestCase
{
    #[TestDox("Shall be valid if prop is LTE maxlength with value \$valBelowMax")]
    #[DataProviderExternal(MaxLengthDataProvider::class, "strBelowMax")]
    public function test1(string $valBelowMax)
    {
        $obj = new class ($valBelowMax)
        {
            #[MaxLength(MaxLengthDataProvider::MAX_LEN)]
            public string $property;

            public function __construct(string $prop)
            {
                $this->property = $prop;
            }
        };

        /**
         * @var MaxLength[] $suts
         */
        $suts = $this->getSuts($obj);
        $foundSut = false;

        foreach ($suts as $sut) {
            $foundSut = true;
            $this->assertTrue($sut->isValid());
        }
        $this->assertTrue($foundSut);
    }

    #[TestDox("Shall be invalid if prop is GT maxlength with value \$valAboveMax")]
    #[DataProviderExternal(MaxLengthDataProvider::class, "strAboveMax")]
    public function test2(string $valAboveMax)
    {
        $obj = new class ($valAboveMax)
        {
            #[MaxLength(MaxLengthDataProvider::MAX_LEN)]
            public string $property;

            public function __construct(string $prop)
            {
                $this->property = $prop;
            }
        };

        /**
         * @var MaxLength[] $suts
         */
        $suts = $this->getSuts($obj);
        $foundSut = false;

        foreach ($suts as $sut) {
            $foundSut = true;
            $this->assertFalse($sut->isValid());
        }
        $this->assertTrue($foundSut);
    }

    #[TestDox("Shall be valid if numeric prop is LTE maxlength with value \$valBelowMax")]
    #[DataProviderExternal(MaxLengthDataProvider::class, "numberBelowMax")]
    public function test3(int|float $valBelowMax)
    {
        $obj = new class ($valBelowMax)
        {
            #[MaxLength(MaxLengthDataProvider::MAX_LEN)]
            public int|float $property;

            public function __construct(int|float $prop)
            {
                $this->property = $prop;
            }
        };

        /**
         * @var MaxLength[] $suts
         */
        $suts = $this->getSuts($obj);

        foreach ($suts as $sut) {
            $foundSut = true;
            $this->assertTrue($sut->isValid());
        }
        $this->assertTrue($foundSut);
    }

    #[TestDox("Shall be invalid if numeric prop is GT max length with value \$valAboveMax")]
    #[DataProviderExternal(MaxLengthDataProvider::class, "numberAboveMax")]
    public function test4(int|float $valAboveMax)
    {
        $obj = new class ($valAboveMax)
        {
            #[MaxLength(MaxLengthDataProvider::MAX_LEN)]
            public int|float $property;

            public function __construct(int|float $prop)
            {
                $this->property = $prop;
            }
        };

        /**
         * @var MaxLength[] $suts
         */
        $suts = $this->getSuts($obj);

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
            #[MaxLength(MaxLengthDataProvider::MAX_LEN)]
            public mixed $property;

            public function __construct(mixed $prop)
            {
                $this->property = $prop;
            }
        };
        /**
         * @var MaxLength[] $suts
         */
        $suts = $this->getSuts($obj);

        foreach ($suts as $sut) {
            $foundSut = true;
            $this->assertTrue($sut->isValid());
        }
        $this->assertTrue($foundSut);
    }

    /**
     * @return MaxLength[]
     */
    private function getSuts(object $obj) : array
    {
        $prop = new ReflectionProperty($obj, "property");
        return array_map(fn (ReflectionAttribute $attr) => $attr->newInstance()->withPropVal($prop, $obj), $prop->getAttributes(MaxLength::class));
    }
}
