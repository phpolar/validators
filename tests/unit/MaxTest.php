<?php

declare(strict_types=1);

namespace Phpolar\Validation;

use Phpolar\Validation\Tests\DataProviders\MaxDataProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionAttribute;
use ReflectionProperty;

#[CoversClass(Max::class)]
final class MaxTest extends TestCase
{
    #[TestDox("Shall be valid if numeric prop is LTE max with value \$valBelowMax")]
    #[DataProviderExternal(MaxDataProvider::class, "numberBelowMax")]
    public function test1(int|float $valBelowMax)
    {
        $obj = new class ($valBelowMax)
        {
            #[Max(MaxDataProvider::MAX)]
            public int|float $property;

            public function __construct(int|float $prop)
            {
                $this->property = $prop;
            }
        };

        /**
         * @var Max[] $suts
         */
        $suts = $this->getSuts($obj);
        $foundSut = false;

        foreach ($suts as $sut) {
            $foundSut = true;
            $this->assertTrue($sut->isValid());
        }
        $this->assertTrue($foundSut);
    }

    #[TestDox("Shall be invalid if numeric prop is GTE max with value \$valAboveMax")]
    #[DataProviderExternal(MaxDataProvider::class, "numberAboveMax")]
    public function test2(int|float $valAboveMax)
    {
        $obj = new class ($valAboveMax)
        {
            #[Max(MaxDataProvider::MAX)]
            public int|float $property;

            public function __construct(int|float $prop)
            {
                $this->property = $prop;
            }
        };

        /**
         * @var Max[] $suts
         */
        $suts = $this->getSuts($obj);
        $foundSut = false;

        foreach ($suts as $sut) {
            $foundSut = true;
            $this->assertFalse($sut->isValid());
        }
        $this->assertTrue($foundSut);
    }

    #[TestDox("Shall be valid if property type is non-numeric")]
    public function testA()
    {
        $obj = new class (null)
        {
            #[Max(MaxDataProvider::MAX)]
            public mixed $property;

            public function __construct(mixed $prop)
            {
                $this->property = $prop;
            }
        };

        /**
         * @var Max[] $suts
         */
        $suts = $this->getSuts($obj);
        $foundSut = false;

        foreach ($suts as $sut) {
            $foundSut = true;
            $this->assertTrue($sut->isValid());
        }
        $this->assertTrue($foundSut);
    }

    /**
     * @return Max[]
     */
    private function getSuts(object $obj) : array
    {
        $prop = new ReflectionProperty($obj, "property");
        return array_map(fn (ReflectionAttribute $attr) => $attr->newInstance()->withPropVal($prop, $obj), $prop->getAttributes(Max::class));
    }
}
