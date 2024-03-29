<?php

declare(strict_types=1);

namespace Phpolar\Validators;

use Phpolar\Validators\Tests\DataProviders\RequiredDataProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionAttribute;
use ReflectionProperty;

#[CoversClass(Required::class)]
final class RequiredTest extends TestCase
{
    #[Test]
    #[DataProviderExternal(RequiredDataProvider::class, "nonEmptyVals")]
    public function shallBeValidIfPropIsSetWithNonEmptyVal(mixed $val)
    {
        $obj = new class($val)
        {
            #[Required]
            public mixed $property;

            public function __construct(mixed $prop)
            {
                $this->property = $prop;
            }
        };

        /**
         * @var Required[] $suts
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
    public function shallBeInvalidIfPropIsNotSet()
    {
        $obj = new class()
        {
            #[Required]
            public mixed $property;

            public function __construct()
            {
            }
        };

        /**
         * @var Required[] $suts
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
    #[DataProviderExternal(RequiredDataProvider::class, "emptyVals")]
    public function shallBeInvalidIfPropIsEmpty(mixed $emptyVals)
    {
        $obj = new class($emptyVals)
        {
            #[Required]
            public mixed $property;

            public function __construct(mixed $emptyVals)
            {
                $this->property = $emptyVals;
            }
        };

        /**
         * @var Required[] $suts
         */
        $suts = $this->getSuts($obj);
        $foundSut = false;

        foreach ($suts as $sut) {
            $foundSut = true;
            $this->assertFalse($sut->isValid());
        }
        $this->assertTrue($foundSut);
    }


    /**
     * @return Required[]
     */
    private function getSuts(object $obj): array
    {
        $prop = new ReflectionProperty($obj, "property");
        return array_map(
            static function (ReflectionAttribute $attr) use ($prop, $obj) {
                /**
                 * @var Required
                 */
                $sut = $attr->newInstance();
                $sut->propVal = $prop->isInitialized($obj) === true ? $prop->getValue($obj) : $prop->getDefaultValue();
                return $sut;
            },
            $prop->getAttributes(Required::class)
        );
    }
}
