<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class StringToBooleanTransformer implements DataTransformerInterface
{

    /**
     * @inheritDoc
     */
    public function transform(mixed $value): mixed
    {
        return empty($value);
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform(mixed $value): mixed
    {
        return $value === true;
    }
}