<?php

namespace Finley\authPlugins\validate;

class StringFieldMustBeHaveValue extends BaseValidate
{
    protected $rule = [
        'checkField' => 'require',
    ];
}
