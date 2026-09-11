<?php

declare(strict_types=1);

namespace App\Validation;

use App\Validation\Rule\DateDebutAvantDateFinRule;
use App\Validation\Rule\DureeMaximaleRule;
use App\Validation\Rule\ReservationDansLeFuturRule;
use App\Validation\Rule\ReservationDateRuleInterface;
use DateTimeImmutable;
use Respect\Validation\Validator as v;

final class ReservationValidator implements ValidatorInterface
{
    /** @var ReservationDateRuleInterface[] */
    private readonly array $reglesDate;

    /**
     * @param ReservationDateRuleInterface[]|null $reglesDate Permet d'injecter
     *        d'autres règles (ou des doublures) depuis le conteneur ou les tests.
     *        Si null, la liste par défaut du projet est utilisée.
     */
    public function __construct(?array $reglesDate = null)
    {
        $this->reglesDate = $reglesDate ?? [
            new DateDebutAvantDateFinRule(),
            new DureeMaximaleRule(),
            new ReservationDansLeFuturRule(),
        ];
    }

    public function validate(array $data): ValidationResult
    {
        $errors = $this->validerChamps($data);
        $errors = array_merge($errors, $this->validerDates($data));

        return new ValidationResult($errors, $errors === [] ? $data : []);
    }

    private function validerChamps(array $data): array
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

        return $errors;
    }

    /**
     * Ancienne version : une dizaine de if imbriqués.
     * Nouvelle version : on parcourt une liste de règles interchangeables
     * (Strategy pattern) qui implémentent toutes ReservationDateRuleInterface.
     * Ajouter une règle = ajouter une classe, sans toucher à cette méthode.
     */
    private function validerDates(array $data): array
    {
        $debut = $this->parseDate($data['date_debut'] ?? null);
        $fin = $this->parseDate($data['date_fin'] ?? null);

        $errors = [];

        if ($debut === null) {
            $errors['date_debut'] = ['La date de début est invalide.'];
        }

        if ($fin === null) {
            $errors['date_fin'] = ['La date de fin est invalide.'];
        }

        if ($debut === null || $fin === null) {
            return $errors;
        }

        foreach ($this->reglesDate as $regle) {
            $erreur = $regle->verifier($debut, $fin);

            if ($erreur !== null) {
                $errors[$erreur['champ']] = [$erreur['message']];
            }
        }

        return $errors;
    }

    private function parseDate(mixed $value): ?DateTimeImmutable
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

            $date = DateTimeImmutable::createFromFormat($format, $value);

            if ($date !== false) {
                return $date;
            }
        }

        return null;
    }
}
