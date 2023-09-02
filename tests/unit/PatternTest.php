<?php

declare(strict_types=1);

namespace Phpolar\Validators;

use Phpolar\Validators\Tests\DataProviders\PatternDataProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionAttribute;
use ReflectionProperty;

#[CoversClass(Pattern::class)]
#[CoversClass(AbstractValidator::class)]
final class PatternTest extends TestCase
{
    #[Test]
    #[DataProviderExternal(PatternDataProvider::class, "validEmails")]
    public function shallBeValidEmailBasedOnPattern(string $val)
    {
        $obj = new class($val)
        {
            #[Pattern(PatternDataProvider::EMAIL_PATTERN)]
            public mixed $property;

            public function __construct(mixed $prop)
            {
                $this->property = $prop;
            }
        };

        /**
         * @var Pattern[] $suts
         */
        $suts = $this->getSuts($obj);
        $this->assertNotEmpty($suts);
        foreach ($suts as $sut) {
            $this->assertTrue($sut->isValid());
            $this->assertEmpty($sut->getMessage());
        }
    }

    #[Test]
    #[DataProviderExternal(PatternDataProvider::class, "validPhoneNumbers")]
    public function shallBeValidPhoneNumberBasedOnPattern(string $val)
    {
        $obj = new class($val)
        {
            #[Pattern(PatternDataProvider::PHONE_PATTERN)]
            public mixed $property;

            public function __construct(mixed $prop)
            {
                $this->property = $prop;
            }
        };

        /**
         * @var Pattern[] $suts
         */
        $suts = $this->getSuts($obj);
        $this->assertNotEmpty($suts);
        foreach ($suts as $sut) {
            $this->assertTrue($sut->isValid());
            $this->assertEmpty($sut->getMessage());
        }
    }

    #[Test]
    public function shallBeInvalidIfPropIsNotSet()
    {
        $obj = new class()
        {
            #[Pattern(PatternDataProvider::PHONE_PATTERN)]
            public mixed $property;

            public function __construct()
            {
            }
        };

        /**
         * @var Pattern[] $suts
         */
        $suts = $this->getSuts($obj);
        $this->assertNotEmpty($suts);
        foreach ($suts as $sut) {
            $this->assertFalse($sut->isValid());
            $this->assertNotEmpty($sut->getMessage());
        }
    }

    #[Test]
    #[DataProviderExternal(PatternDataProvider::class, "invalidEmails")]
    public function shallBeInvalidIfPropDoesNotMatchPattern(mixed $val)
    {
        $obj = new class($val)
        {
            #[Pattern(PatternDataProvider::EMAIL_PATTERN)]
            public mixed $property;

            public function __construct(mixed $invalidVal)
            {
                $this->property = $invalidVal;
            }
        };

        /**
         * @var Pattern[] $suts
         */
        $suts = $this->getSuts($obj);
        $this->assertNotEmpty($suts);
        foreach ($suts as $sut) {
            $this->assertFalse($sut->isValid());
            $this->assertNotEmpty($sut->getMessage());
        }
    }

    /**
     * @return Pattern[]
     */
    private function getSuts(object $obj): array
    {
        $prop = new ReflectionProperty($obj, "property");
        $getAttrs = static function (ReflectionAttribute $attr) use ($prop, $obj) {
            $instance = $attr->newInstance();
            $instance->propVal = $prop->isInitialized($obj) === true ? $prop->getValue($obj) : $prop->getDefaultValue();
            return $instance;
        };
        return array_map($getAttrs, $prop->getAttributes(Pattern::class));
    }
}
