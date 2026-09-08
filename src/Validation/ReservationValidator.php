<?php

namespace App\Validation;

use Respect\Validation\Validator as v;

class ReservationValidator implements ValidatorInterface
{
    public function validate(array $data): ValidationResult
    {
        $rules = [
            'salle_id' => v::intType()->positive(),
            'responsable' => v::stringType()->notEmpty(),
            'email' => v::email(),
            'motif' => v::stringType()->length(5, 255),
            'statut' => v::in(['confirmée', 'annulée']),
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

        $start = $this->parseDate($data['date_debut'] ?? null);
        $end = $this->parseDate($data['date_fin'] ?? null);

        if ($start === null) {
            $errors['date_debut'] = ['La date de début est invalide.'];
        }

        if ($end === null) {
            $errors['date_fin'] = ['La date de fin est invalide.'];
        }

        if ($start !== null && $end !== null) {
            $now = new \DateTimeImmutable();

            if ($start <= $now) {
                $errors['date_debut'] = ['La date de début doit être dans le futur.'];
            }

            if ($start >= $end) {
                $errors['date_fin'] = ['La date de fin doit être postérieure à la date de début.'];
            }

            if ($end->getTimestamp() - $start->getTimestamp() > 4 * 60 * 60) {
                $errors['date_fin'] = ['La réservation ne peut pas durer plus de quatre heures.'];
            }
        }

        return new ValidationResult($errors, $errors === [] ? $data : []);
    }

    private function parseDate(mixed $value): ?\DateTimeImmutable
    {
        $formats = [
            'Y-m-d H:i:s',
            'Y-m-d\TH:i',
        ];

        foreach ($formats as $format) {
            $rule = v::stringType()
                ->notEmpty()
                ->dateTime($format);

            if (!$rule->validate($value)) {
                continue;
            }

            $date = \DateTimeImmutable::createFromFormat($format, $value);

            if ($date !== false) {
                return $date;
            }
        }

        return null;
    }
}