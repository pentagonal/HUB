<?php
declare(strict_types=1);

namespace Pentagonal\Hub\Schema\Whmcs\Definitions\Properties;

use stdClass;
use Swaggest\JsonSchema\JsonSchema;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

class Select extends ClassStructure
{
    /**
     * @var string $type should select
     */
    public string $type = 'select';

    /**
     * @var string $name The select name
     */
    public string $name;

    /**
     * @var string $label the label
     */
    public string $label;

    /**
     * @var string $description the select description
     */
    public string $description = '';

    /**
     * @var ?string $placeholder the placeholder of the select
     */
    public ?string $placeholder = null;

    /**
     * @var bool $multiple true if multiple support
     */
    public bool $multiple = false;

    /**
     * @var bool $required attribute require
     */
    public bool $required = false;

    /**
     * @var array $options the options
     */
    public array $options = [];

    /**
     * @var object $attributes the attributes
     */
    public object $attributes;

    public function __construct()
    {
        $this->attributes = new stdClass();
    }

    /**
     * @noinspection PhpUndefinedFieldInspection
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $ownerSchema
            ->setType(JsonSchema::OBJECT)
            ->setDescription('The select attributes of the definition')
            ->setAdditionalProperties(Schema::object()->setRef('#/definitions/htmlTagAttributes'))
            ->setRequired([
                'name',
                'label',
                'options',
            ]);

        $properties->type = Schema::string()
            ->setDescription('The type of the select')
            ->setDefault('select')
            ->setEnum([
                'select',
            ]);

        $properties->name = Schema::string()
            ->setDescription('The name of the select');
        $properties->label = Schema::string()
            ->setDescription('The label of the select');
        $properties->description = Schema::string()
            ->setDescription('The description of the select')
            ->setDefault('');
        $properties->placeholder = Schema::create()
            ->setType([
                JsonSchema::STRING,
                JsonSchema::NULL,
            ])
            ->setDescription('The placeholder of the select')
            ->setDefault(null);
        $properties->multiple = Schema::boolean()
            ->setDescription('The multiple attribute of the select')
            ->setDefault(false);
        $properties->required = Schema::boolean()
            ->setDescription('The required attribute of the select')
            ->setDefault(false);
        $properties->options = Schema::create()
            ->setOneOf([
                Schema::object()
                    ->setRequired([
                        'label',
                        'value',
                    ])
                    ->setItems(
                        Schema::create()
                            ->setType(JsonSchema::_ARRAY)
                            ->setAdditionalProperties(false)
                            ->setProperty('label', Schema::string())
                            ->setProperty('value', Schema::string())
                            ->setProperty('disabled', Schema::boolean())
                            ->setProperty('selected', Schema::boolean())
                            ->setRequired([
                                'label',
                                'value',
                            ])
                    )
                    ->setDescription('The options of the select'),
                Schema::object()
                    ->setDescription('The optgroup of the select')
                    ->setAdditionalProperties(false)
                    ->setProperty(
                        'label',
                        Schema::string()
                            ->setDescription('Label of optgroup')
                    )
                    ->setProperty('type', Schema::string()->setDefault('optgroup'))
                    ->setProperty('options', Schema::create()->setRef('#/definitions/select/properties/options'))
                    ->setRequired([
                        'label',
                        'type',
                        'options',
                    ])
            ])
            ->setType(JsonSchema::_ARRAY);
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
