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
        $obj = new class($valAboveMin)
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
        $this->assertNotEmpty($suts);
        foreach ($suts as $sut) {
            $this->assertTrue($sut->isValid());
            $this->assertEmpty($sut->getMessages());
        }
    }

    #[Test]
    #[DataProviderExternal(MinDataProvider::class, "numberBelowMin")]
    public function shallBeInvalidIfNumericPropIsGtMax(int|float $valBelowMin)
    {
        $obj = new class($valBelowMin)
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
        $this->assertNotEmpty($suts);
        foreach ($suts as $sut) {
            $this->assertFalse($sut->isValid());
            $this->assertNotEmpty($sut->getMessages());
        }
    }

    #[TestDox("Shall be valid if property type is non-numeric")]
    public function testA()
    {
        $obj = new class(null)
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
        $this->assertNotEmpty($suts);
        foreach ($suts as $sut) {
            $this->assertTrue($sut->isValid());
            $this->assertEmpty($sut->getMessages());
        }
    }

    /**
     * @return Min[]
     */
    private function getSuts(object $obj): array
    {
        $prop = new ReflectionProperty($obj, "property");
        $getAttrs = static function (ReflectionAttribute $attr) use ($prop, $obj) {
            $instance = $attr->newInstance();
            $instance->propVal = $prop->isInitialized($obj) === true ? $prop->getValue($obj) : $prop->getDefaultValue();
            return $instance;
        };
        return array_map($getAttrs, $prop->getAttributes(Min::class));
    }
}
