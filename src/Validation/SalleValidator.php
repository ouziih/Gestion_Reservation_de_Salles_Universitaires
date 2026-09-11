<?php

namespace App\Validation;

use Respect\Validation\Validator as v;

class SalleValidator implements ValidatorInterface
{
    public function validate(array $data): ValidationResult
    {
        $rules = [
            'nom' => v::stringType()->notEmpty(),
            'batiment' => v::stringType()->notEmpty(),
            'capacite' => v::intType()->positive(),
            'type' => v::in([
                'cours',
                'informatique',
                'laboratoire',
                'amphitheatre',
                'reunion',
            ]),
            'active' => v::boolType(),
        ];

        $errors = [];

        foreach ($rules as $field => $rule) {
            if (!array_key_exists($field, $data)) {
                $errors[$field] = ['Le champ est obligatoire.'];
                continue;
            }

            if (!$rule->validate($data[$field])) {
                $errors[$field] = ['La valeur est invalide.'];
            }
        }

        return new ValidationResult($errors, $errors === [] ? $data : []);
    }
}