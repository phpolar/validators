<?php

declare(strict_types=1);

namespace Phpolar;

use Phpolar\Validator\MessageGetterInterface;
use Phpolar\Validators\MaxLength;
use Phpolar\Validators\Pattern;
use Phpolar\Validators\Required;
use Phpolar\Validators\Tests\DataProviders\MaxLengthDataProvider;
use Phpolar\Validator\ValidatorInterface;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionAttribute;
use ReflectionObject;

use const \Phpolar\Tests\PROJECT_MEMORY_USAGE_THRESHOLD;

#[CoversNothing]
final class MemoryUsageTest extends TestCase
{
    #[Test]
    #[TestDox("Memory usage shall be below \$projectMemoryUsageThreshold bytes")]
    public function shallBeBelowThreshold1(string $projectMemoryUsageThreshold = PROJECT_MEMORY_USAGE_THRESHOLD)
    {
        $valBelowMax = str_repeat("a", MaxLengthDataProvider::MAX_LEN - 1);
        $obj = new class($valBelowMax)
        {
            #[MaxLength(MaxLengthDataProvider::MAX_LEN)]
            #[Required]
            public string $property;

            #[Pattern("/^[[:alpha:]]$/")]
            public string $name = "somebody";

            public function __construct(string $prop)
            {
                $this->property = $prop;
            }
        };

        $totalUsed = -memory_get_usage();
        $this->checkAttrValidators($obj);
        $totalUsed += memory_get_usage();
        $this->assertGreaterThan(0, $totalUsed);
        $this->assertLessThanOrEqual((int) $projectMemoryUsageThreshold, $totalUsed);
    }

    private function checkAttrValidators(object $obj): void
    {
        $reflectionObj = new ReflectionObject($obj);
        $props = $reflectionObj->getProperties();

        foreach ($props as $prop) {
            /**
             * @var (ValidatorInterface&MessageGetterInterface)[] $validators
             */
            $validators = array_map(
                static function (ReflectionAttribute $attr) use ($prop, $obj) {
                    $instance = $attr->newInstance();
                    if (property_exists($obj, "withRequiredPropVal") === true) {
                        return $instance->withRequiredPropVal($prop, $obj);
                    }
                    $instance->propVal = $prop->isInitialized($obj) === true ? $prop->getValue($obj) : $prop->getDefaultValue();
                    return $instance;
                },
                $prop->getAttributes()
            );
            foreach ($validators as $validator) {
                $validator->isValid();
                $validator->getMessages();
            }
        }
    }
}
