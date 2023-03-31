<?php

declare(strict_types=1);

namespace Phpolar\Validation;

use Phpolar\Validation\Tests\DataProviders\PatternDataProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionAttribute;
use ReflectionProperty;

#[CoversClass(Pattern::class)]
#[CoversClass(AbstractPropertyValueExtractor::class)]
final class PatternTest extends TestCase
{
    #[Test]
    #[DataProviderExternal(PatternDataProvider::class, "validEmails")]
    public function shallBeValidEmailBasedOnPattern(string $val)
    {
        $obj = new class ($val)
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
        $foundSut = false;

        foreach ($suts as $sut) {
            $foundSut = true;
            $this->assertTrue($sut->isValid());
        }
        $this->assertTrue($foundSut);
    }

    #[Test]
    #[DataProviderExternal(PatternDataProvider::class, "validPhoneNumbers")]
    public function shallBeValidPhoneNumberBasedOnPattern(string $val)
    {
        $obj = new class ($val)
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
        $obj = new class ()
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
        $foundSut = false;

        foreach ($suts as $sut) {
            $foundSut = true;
            $this->assertFalse($sut->isValid());
        }
        $this->assertTrue($foundSut);
    }

    #[Test]
    #[DataProviderExternal(PatternDataProvider::class, "invalidEmails")]
    public function shallBeInvalidIfPropDoesNotMatchPattern(mixed $val)
    {
        $obj = new class ($val)
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
        $foundSut = false;

        foreach ($suts as $sut) {
            $foundSut = true;
            $this->assertFalse($sut->isValid());
        }
        $this->assertTrue($foundSut);
    }

    /**
     * @return Pattern[]
     */
    private function getSuts(object $obj) : array
    {
        $prop = new ReflectionProperty($obj, "property");
        return array_map(fn (ReflectionAttribute $attr) => $attr->newInstance()->withPropVal($prop, $obj), $prop->getAttributes(Pattern::class));
    }
}
