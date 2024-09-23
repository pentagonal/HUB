<?php
declare(strict_types=1);

namespace Pentagonal\Hub\Schema\Whmcs\Definitions\Properties;

use stdClass;
use Swaggest\JsonSchema\JsonSchema;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

class Checkbox extends ClassStructure
{
    /**
     * @var string $type should checkbox
     */
    public string $type = 'checkbox';

    /**
     * @var string $name The input name
     */
    public string $name;

    /**
     * @var string $label the label
     */
    public string $label;

    /**
     * @var string $description the checkbox description
     */
    public string $description = '';

    /**
     * @var string $value the checkbox value
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
     * @var bool $checked true if checkbox checked
     */
    public bool $checked = false;

    /**
     * @var object $attributes the attributes
     */
    public object $attributes;

    public function __construct()
    {
        $this->attributes = new stdClass();
    }

    /**
     * Setup
     *
     * @param \Swaggest\JsonSchema\Constraint\Properties $properties
     * @param Schema $ownerSchema
     * @return void
     * @throws \Swaggest\JsonSchema\Exception
     * @noinspection PhpUndefinedFieldInspection
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $ownerSchema
            ->setType(JsonSchema::OBJECT)
            ->setDescription('The input checkbox attributes of the definition')
            ->setAdditionalProperties(Schema::object()->setRef('#/definitions/htmlTagAttributes'))
            ->setRequired([
                'name',
                'label'
            ]);

        $properties->type = Schema::string()
            ->setDescription('The type of the checkbox')
            ->setDefault('checkbox')
            ->setEnum([
                'checkbox',
            ]);

        $properties->name = Schema::string()
            ->setDescription('The name of the checkbox');
        $properties->label = Schema::string()
            ->setDescription('The label of the checkbox');
        $properties->description = Schema::string()
            ->setDescription('The description of the checkbox')
            ->setDefault('');
        $properties->value = Schema::string()
            ->setDescription('The value of the checkbox')
            ->setDefault('1');
        $properties->multiple = Schema::boolean()
            ->setDescription('The multiple attribute of the checkbox');
        $properties->required = Schema::boolean()
            ->setDescription('The required attribute of the checkbox')
            ->setDefault(false);
        $properties->checked = Schema::boolean()
            ->setDescription('The checked attribute of the checkbox')
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
