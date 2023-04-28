<?php declare(strict_types=1);

use DI\ContainerBuilder;
use Valitron\Validator;

return static function (ContainerBuilder $builder) {
    Validator::addRule('address', function($field, $value, array $params, array $fields) {
        if (!is_string($value)) {
            return false;
        }

        if (str_contains($value, '.ton')) {
            try {
                return \Olifanton\Ton\Dns\Helpers\DomainHelper::domainToBytes($value)->length > 0;
            } catch (\Olifanton\Ton\Dns\Exceptions\DnsException $e) {
                return false;
            }
        }

        return \Olifanton\Interop\Address::isValid($value);
    }, '{field} is not valid Address');
};
