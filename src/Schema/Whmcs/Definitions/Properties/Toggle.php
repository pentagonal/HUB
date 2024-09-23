<?php
declare(strict_types=1);

namespace Pentagonal\Hub\Schema\Whmcs\Definitions\Properties;

use stdClass;
use Swaggest\JsonSchema\JsonSchema;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

class Toggle extends ClassStructure
{
    /**
     * @var string $type should toggle
     */
    public string $type = 'toggle';

    /**
     * @var string $name The input name
     */
    public string $name;

    /**
     * @var string $label the label
     */
    public string $label;

    /**
     * @var string $description the toggle description
     */
    public string $description = '';

    /**
     * @var string $value the toggle value
     */
    public string $value = '1';

    /**
     * @var bool $multiple true if multiple support
     */
    public bool $multiple = false;

    /**
     * @var bool $required attribute require
     */
    public bool $required = false;

    /**
     * @var bool $checked true if toggle checked
     */
    public bool $checked = false;

    /**
     * @var object $attributes the attributes
     */
    public object $attributes;

    /**
     * @noinspection PhpUndefinedFieldInspection
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $ownerSchema
            ->setType(JsonSchema::OBJECT)
            ->setDescription('The toggle checkbox attributes of the definition')
            ->setAdditionalProperties(Schema::object()->setRef('#/definitions/htmlTagAttributes'))
            ->setRequired([
                'name',
                'label'
            ]);

        $properties->type = Schema::string()
            ->setDescription('The type of the toggle')
            ->setDefault('toggle')
            ->setEnum([
                'toggle',
            ]);

        $properties->name = Schema::string()
            ->setDescription('The name of the toggle');
        $properties->label = Schema::string()
            ->setDescription('The label of the toggle');
        $properties->description = Schema::string()
            ->setDescription('The description of the toggle')
            ->setDefault('');
        $properties->value = Schema::string()
            ->setDescription('The value of the toggle')
            ->setDefault('1')
            ->setEnum([
                '1',
                '0',
            ]);
        $properties->multiple = Schema::boolean()
            ->setDescription('The multiple attribute of the toggle')
            ->setDefault(false)
            ->setEnum([
                false
            ]);
        $properties->required = Schema::boolean()
            ->setDescription('The required attribute of the toggle')
            ->setDefault(false)
            ->setEnum([
                false
            ]);
        $properties->checked = Schema::boolean()
            ->setDescription('The checked attribute of the toggle')
            ->setDefault(false);
        /** @noinspection PhpUnhandledExceptionInspection */
        $properties->attributes = Schema::object()
            ->setDescription('The attributes')
            ->setPatternProperty(
                '^([a-z]+([a-z0-9-]*[a-z]+)?)$',
                Schema::create()->setType([
                    JsonSchema::STRING,
                    JsonSchema::INTEGER,
                    JsonSchema::BOOLEAN,
                    JsonSchema::NULL,
                ])
            )->setDefault(new stdClass());
    }
}
