<?php

declare(strict_types=1);

namespace Phpolar\Validators;

use Phpolar\Validators\Tests\DataProviders\MaxDataProvider;
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
        $obj = new class($valBelowMax)
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
        $this->assertNotEmpty($suts);
        foreach ($suts as $sut) {
            $this->assertTrue($sut->isValid());
            $this->assertEmpty($sut->getMessages());
        }
    }

    #[TestDox("Shall be invalid if numeric prop is GTE max with value \$valAboveMax")]
    #[DataProviderExternal(MaxDataProvider::class, "numberAboveMax")]
    public function test2(int|float $valAboveMax)
    {
        $obj = new class($valAboveMax)
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
        $this->assertNotEmpty($suts);
        foreach ($suts as $sut) {
            $this->assertTrue($sut->isValid());
            $this->assertEmpty($sut->getMessages());
        }
    }

    /**
     * @return Max[]
     */
    private function getSuts(object $obj): array
    {
        $prop = new ReflectionProperty($obj, "property");
        $getSuts = static function (ReflectionAttribute $attr) use ($prop, $obj) {
            $instance = $attr->newInstance();
            $instance->propVal = $prop->isInitialized($obj) === true ? $prop->getValue($obj) : $prop->getDefaultValue();
            return $instance;
        };
        return array_map($getSuts, $prop->getAttributes(Max::class));
    }
}
