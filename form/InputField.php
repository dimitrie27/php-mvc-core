<?php

namespace dimmvc\phpmvc\form;

use dimmvc\phpmvc\Model;

class InputField extends BaseField
{
    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';
    public const TYPE_EMAIL = 'email';
    public string $type;
    public string $customProp = '';

    public function __construct(Model $model, string $attribute)
    {
        $this->type = self::TYPE_TEXT;
        parent::__construct($model, $attribute);
    }

    public function passwordField()
    {
        $this->type = self::TYPE_PASSWORD;

        return $this;
    }

    public function emailField()
    {
        $this->type = self::TYPE_EMAIL;

        return $this;
    }

    public function numberField()
    {
        $this->type = self::TYPE_NUMBER;
        $this->customProp = 'min="0"';

        return $this;
    }

    public function renderInput(): string
    {
        return sprintf('<input type="%s" name="%s" value="%s" class="form-control%s" %s>',
            $this->type,
            $this->attribute,
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->customProp
        );
    }   
}