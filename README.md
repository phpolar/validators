<p align="center">
  <a href="https://github.com/phpolar"><img src="phpolar.svg" width="240" alt="Phpolar Logo" /></a>
</p>

# Validation

A set of property validators.

## Usage

```php
class Something
{
        #[Required]
        #[Pattern("/^[A-Za-z]$/")]
        public string $name;

        #[Max(12)]
        #[Min(2)]
        public int $age;
}


$validationAttr->isValid();

```
