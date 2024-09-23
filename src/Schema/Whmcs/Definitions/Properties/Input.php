<?php
declare(strict_types=1);

namespace Pentagonal\Hub\Schema\Whmcs\Definitions\Properties;

use stdClass;
use Swaggest\JsonSchema\Constraint\Format;
use Swaggest\JsonSchema\JsonSchema;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

class Input extends ClassStructure
{

    /**
     * @var string $type should input
     */
    public string $type = 'input';

    /**
     * @var string $name The input name
     */
    public string $name;

    /**
     * @var string $input the input type default text
     */
    public string $input = 'text';

    /**
     * @var ?string $label the label
     */
    public ?string $label = null;

    /**
     * @var string $description the input description
     */
    public string $description = '';

    /**
     * @var string $default the default value
     */
    public string $default = '';

    /**
     * @var string $value the input value
     */
    public string $value = '';

    /**
     * @var ?string $placeholder the placeholder of the input
     */
    public ?string $placeholder = null;

    /**
     * @var bool $required attribute require
     */
    public bool $required = false;

    /**
     * @var bool $disabled attribute disabled
     */
    public bool $disabled = false;

    /**
     * @var bool $readonly attribute readonly
     */
    public bool $readonly = false;

    /**
     * @var bool $multiple true if multiple support
     */
    public bool $multiple = false;

    /**
     * @var ?string $pattern the pattern attribute
     */
    public ?string $pattern = null;

    /**
     * @var ?int $minlength the minlength attribute
     */
    public ?int $minlength = null;

    /**
     * @var ?int $maxlength the maxlength attribute
     */
    public ?int $maxlength = null;

    /**
     * @var ?int $min the min attribute
     */
    public ?int $min = null;

    /**
     * @var ?int $max the max attribute
     */
    public ?int $max = null;

    /**
     * @var ?int $step the step attribute
     */
    public ?int $step = null;

    /**
     * @var ?int $size the size attribute
     */
    public ?int $size = null;

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
            ->setDescription('The input attributes of the definition')
            ->setAdditionalProperties(Schema::object()->setRef('#/definitions/htmlTagAttributes'))
            ->setRequired([
                'name',
            ]);
        $properties->type = Schema::string()
            ->setDescription('The type of the input')
            ->setDefault('input')
            ->setEnum([
                'input',
            ]);
        $properties->name = Schema::string()
            ->setDescription('The name of the input');
        $properties->input = Schema::string()
            ->setDescription('The input type of the input')
            ->setDefault('text')
            ->setEnum([
                'text',
                'password',
                'email',
                'number',
                'tel',
                'url',
                'search',
                'date',
                'time',
                'datetime-local',
                'month',
                'week',
                'color',
                'file',
                'hidden',
                'image',
                'range',
                'reset',
                'submit',
            ]);

        $properties->label = Schema::create()
            ->setType([
                JsonSchema::STRING,
                JsonSchema::NULL,
            ])
            ->setDescription('The label of the input')
            ->setDefault(null);
        $properties->description = Schema::string()
            ->setDescription('The description of the input')
            ->setDefault('');
        $properties->value = Schema::string()
            ->setDescription('The value of the input')
            ->setDefault('');
        $properties->placeholder = Schema::create()
            ->setType([
                JsonSchema::STRING,
                JsonSchema::NULL,
            ])
            ->setDescription('The placeholder of the input')
            ->setDefault(null);
        $properties->required = Schema::boolean()
            ->setDescription('The required attribute of the input')
            ->setDefault(false);
        $properties->disabled = Schema::boolean()
            ->setDescription('The disabled attribute of the input')
            ->setDefault(false);
        $properties->readonly = Schema::boolean()
            ->setDescription('The readonly attribute of the input')
            ->setDefault(false);
        $properties->multiple = Schema::boolean()
            ->setDescription('The multiple attribute of the input')
            ->setDefault(false);
        $properties->pattern = Schema::string()
            ->setFormat(Format::REGEX)
            ->setDescription('The pattern attribute of the input')
            ->setDefault(null);
        $properties->default = Schema::string()
            ->setDescription('The default value of the input')
            ->setDefault('');
        $properties->minlength = Schema::create()
            ->setDescription('The minlength attribute of the input')
            ->setType([
                JsonSchema::INTEGER,
                JsonSchema::NULL,
            ])
            ->setDefault(null);
        $properties->maxlength = Schema::create()
            ->setDescription('The maxlength attribute of the input')
            ->setType([
                JsonSchema::INTEGER,
                JsonSchema::NULL,
            ])
            ->setDefault(null);
        $properties->min = Schema::create()
            ->setDescription('The min attribute of the input')
            ->setType([
                JsonSchema::INTEGER,
                JsonSchema::NULL,
            ])
            ->setDefault(null);
        $properties->max = Schema::create()
            ->setDescription('The max attribute of the input')
            ->setType([
                JsonSchema::INTEGER,
                JsonSchema::NULL,
            ])
            ->setDefault(null);
        $properties->step = Schema::create()
            ->setDescription('The step attribute of the input')
            ->setType([
                JsonSchema::INTEGER,
                JsonSchema::NULL,
            ])
            ->setDefault(null);
        $properties->size = Schema::create()
            ->setDescription('The size attribute of the input')
            ->setType([
                JsonSchema::INTEGER,
                JsonSchema::NULL,
            ])
            ->setDefault(null);
        /** @noinspection PhpUnhandledExceptionInspection */
        $properties->attributes = Schema::object()
            ->setDescription('The attributes')
            ->setNot(
                Schema::object()->setRef('#/definitions/htmlTagAttributes')
            )
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
