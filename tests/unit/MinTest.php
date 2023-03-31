<?php

declare(strict_types=1);

namespace Phpolar\Validators;

use Phpolar\Validators\Tests\DataProviders\MinDataProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionAttribute;
use ReflectionProperty;

#[CoversClass(Min::class)]
final class MinTest extends TestCase
{
    #[Test]
    #[DataProviderExternal(MinDataProvider::class, "numberAboveMin")]
    public function shallBeValidIfNumericPropIsLteMax(int|float $valAboveMin)
    {
        $obj = new class ($valAboveMin)
        {
            #[Min(MinDataProvider::MIN)]
            public int|float $property;

            public function __construct(int|float $prop)
            {
                $this->property = $prop;
            }
        };

        /**
         * @var Min[] $suts
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
    #[DataProviderExternal(MinDataProvider::class, "numberBelowMin")]
    public function shallBeInvalidIfNumericPropIsGtMax(int|float $valBelowMin)
    {
        $obj = new class ($valBelowMin)
        {
            #[Min(MinDataProvider::MIN)]
            public int|float $property;

            public function __construct(int|float $prop)
            {
                $this->property = $prop;
            }
        };

        /**
         * @var Min[] $suts
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
            #[Min(MinDataProvider::MIN)]
            public mixed $property;

            public function __construct(mixed $prop)
            {
                $this->property = $prop;
            }
        };

        /**
         * @var Min[] $suts
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
     * @return Min[]
     */
    private function getSuts(object $obj) : array
    {
        $prop = new ReflectionProperty($obj, "property");
        return array_map(fn (ReflectionAttribute $attr) => $attr->newInstance()->withPropVal($prop, $obj), $prop->getAttributes(Min::class));
    }
}
