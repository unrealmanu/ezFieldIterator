<?php


namespace unrealmanu\ezFieldIterator;

use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\Field;
use Symfony\Component\HttpKernel\Bundle\Bundle;

use Generator;

class Filters extends Bundle
{
    /**
     * @var string
     */
    private $valueField;

    /**
     * @var mixed
     */
    private $class;

    /**
     * @var Content
     */
    private $content;

    /**
     * @var string|null
     */
    private $fieldDefIdentifier;

    /**
     * @return array
     */
    public function getChildren(): array
    {
        return iterator_to_array($this->getFieldByClass());
    }

    /**
     * @return string|null
     */
    public function getFirstChildren(): ?string
    {
        $result = $this->getChildren();

        return $result[array_key_first($result)];
    }

    /**
     * @return string
     */
    public function getConcatTextChildren(): string
    {
        $result = $this->getChildren();
        if (is_array($this->fieldDefIdentifier)) {
            return implode(' ', $result);
        } else {
            $this->getFirstChildren();
        }
    }

    /**
     * @return Generator
     */
    private function getFieldByClass(): Generator
    {
        foreach ($this->content->getFields() as $k => $field) {
            $result = $this->getValueOfFieldByClass($field);
            if ($result) {
                yield $result;
            }
        }
    }

    /**
     * @param Field $field
     *
     * @return mixed
     */
    private function getValueOfFieldByClass(Field $field)
    {
        $valueField = $this->valueField;
        if ($this->instanceOfFilter($field->value, $this->class)) {
            if ($this->fieldRequirements($field)) {
                return $field->value->$valueField;
            }
        }
    }

    /**
     * @param Field $field
     *
     * @return bool
     */
    private function fieldRequirements(Field $field): bool
    {
        if (null !== $this->fieldDefIdentifier) {
            //IN CASE OF ARRAY
            if (is_array($field->fieldDefIdentifier)) {
                if (!in_array($field->fieldDefIdentifier, $this->fieldDefIdentifier)) {
                    return false;
                }
            } else {
                if (!$field->fieldDefIdentifier == $this->fieldDefIdentifier) {
                    return false;
                }
            }
        }

        if (!property_exists($field->value, $this->valueField)) {
            return false;
        }

        return true;
    }

    /**
     * @param $obj
     * @param mixed $list
     *
     * @return bool
     */
    private function instanceOfFilter($obj, $list): bool
    {
        if (is_array($list)) {
            foreach ($list as $k => $instance) {
                if ($obj instanceof $instance) {
                    return true;
                }
            }
        } else {
            if ($obj instanceof $list) {
                return true;
            }
        }

        return false;
    }

    // SETTERS

    /**
     * @param string $valueField
     */
    public function setValueField(string $valueField): void
    {
        $this->valueField = $valueField;
    }

    /**
     * @param mixed $class
     */
    public function setClass($class): void
    {
        $this->class = $class;
    }

    /**
     * @param Content $content
     */
    public function setContent(Content $content): void
    {
        $this->content = $content;
    }

    /**
     * @param mixed $fieldDefIdentifier
     */
    public function setFieldDefIdentifier($fieldDefIdentifier): void
    {
        $this->fieldDefIdentifier = $fieldDefIdentifier;
    }
}
