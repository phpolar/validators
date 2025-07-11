<p align="center">
  <a href="https://github.com/phpolar"><img src="phpolar.svg" width="240" alt="Phpolar Logo" /></a>
</p>

# Validators

A set of property validators.

[![Version](https://poser.pugx.org/phpolar/validators/version)](https://packagist.org/packages/phpolar/validators) [![PHP Version Require](https://poser.pugx.org/phpolar/validators/require/php)](https://packagist.org/packages/phpolar/validators) [![Total Downloads](https://poser.pugx.org/phpolar/validators/downloads)](https://packagist.org/packages/phpolar/validators) [![Weekly Check](https://github.com/phpolar/validators/actions/workflows/weekly.yml/badge.svg)](https://github.com/phpolar/validators/actions/workflows/weekly.yml) [![Source code only changes](https://github.com/phpolar/validators/actions/workflows/source-changes.yml/badge.svg)](https://github.com/phpolar/validators/actions/workflows/source-changes.yml)

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

## [API Documentation](https://phpolar.github.io/validators/)
