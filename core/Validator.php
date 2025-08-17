<?php

namespace App\Core;

class Validator
{
    protected array $data;
    protected array $rules;
    protected array $errors = [];

    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->validate();
    }

    protected function validate(): void
    {
        foreach ($this->rules as $field => $rules) {
            $rules = explode('|', $rules);
            $value = $this->data[$field] ?? null;

            foreach ($rules as $rule) {
                $ruleName = $rule;
                $param = null;

                if (str_contains($rule, ':')) {
                    [$ruleName, $param] = explode(':', $rule, 2);
                }

                $method = "validate" . ucfirst($ruleName);

                if (method_exists($this, $method)) {
                    $this->$method($field, $value, $param);
                }
            }
        }
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    protected function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    // ---- Validation Rules ----

    protected function validateRequired(string $field, $value): void
    {
        if (is_null($value) || $value === '') {
            $this->addError($field, "$field is required.");
        }
    }

    protected function validateEmail(string $field, $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, "$field must be a valid email.");
        }
    }

    protected function validateMin(string $field, $value, $param): void
    {
        if (strlen((string) $value) < (int) $param) {
            $this->addError($field, "$field must be at least $param characters.");
        }
    }

    protected function validateMax(string $field, $value, $param): void
    {
        if (strlen((string) $value) > (int) $param) {
            $this->addError($field, "$field must not exceed $param characters.");
        }
    }

    protected function validateNumeric(string $field, $value): void
    {
        if (!is_numeric($value)) {
            $this->addError($field, "$field must be numeric.");
        }
    }
}
