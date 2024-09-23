<?php
declare(strict_types=1);

namespace Pentagonal\Hub\Schema\Whmcs\Definitions\Properties;

use stdClass;
use Swaggest\JsonSchema\JsonSchema;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

class TextArea extends ClassStructure
{
    /**
     * @var string $type should textarea
     */
    public string $type = 'textarea';

    /**
     * @var string $name The name of the textarea
     */
    public string $name;

    /**
     * @var string $label The label of the textarea
     */
    public string $label;

    /**
     * @var string $description The description of the textarea
     */
    public string $description = '';

    /**
     * @var string $default The default value of the textarea
     */
    public string $default = '';

    /**
     * @var ?string $placeholder The placeholder of the textarea
     */
    public ?string $placeholder = null;

    /**
     * @var string $value The value of the textarea
     */
    public string $value = '';

    /**
     * @var bool $required The required attribute of the textarea
     */
    public bool $required = false;

    /**
     * @var bool $disabled The disabled attribute of the textarea
     */
    public bool $disabled = false;

    /**
     * @var bool $readonly The readonly attribute of the textarea
     */
    public bool $readonly = false;

    /**
     * @var ?int $rows The rows of the textarea
     */
    public ?int $rows = null;

    /**
     * @var ?int $cols The cols of the textarea
     */
    public ?int $cols = null;

    /**
     * @var ?int $maxlength The maxlength attribute of the textarea
     */
    public ?int $maxlength = null;

    /**
     * @var ?int $minlength The minlength attribute of the textarea
     */
    public ?int $minlength = null;

    /**
     * @var object $attributes The attributes
     */
    public object $attributes;

    public function __construct()
    {
        $this->attributes = new stdClass();
    }

    /**
     * @throws \Exception
     * @noinspection PhpFullyQualifiedNameUsageInspection
     * @noinspection PhpUndefinedFieldInspection
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $ownerSchema
            ->setType(JsonSchema::OBJECT)
            ->setDescription('The textarea attributes of the definition')
            ->setAdditionalProperties(Schema::object()->setRef('#/definitions/htmlTagAttributes'))
            ->setRequired([
                'name',
                'label',
            ]);
        $properties->type = Schema::string()
            ->setDescription('The type of the textarea')
            ->setDefault('textarea')
            ->setEnum([
                'textarea',
            ]);
        $properties->name = Schema::string()
            ->setDescription('The name of the textarea');
        $properties->label = Schema::string()
            ->setDescription('The label of the textarea');
        $properties->description = Schema::string()
            ->setDescription('The description of the textarea')
            ->setDefault('');
        $properties->default = Schema::create()
            ->setType([
                JsonSchema::STRING,
                JsonSchema::NULL,
            ])
            ->setDescription('The default value of the textarea');
        $properties->placeholder = Schema::string()
            ->setDescription('The placeholder of the textarea')
            ->setDefault(null);
        $properties->value = Schema::string()
            ->setDescription('The value of the textarea');
        $properties->required = Schema::boolean()
            ->setDescription('The required attribute of the textarea')
            ->setDefault(false);
        $properties->disabled = Schema::boolean()
            ->setDescription('The disabled attribute of the textarea')
            ->setDefault(false);
        $properties->readonly = Schema::boolean()
            ->setDescription('The readonly attribute of the textarea')
            ->setDefault(false);
        $properties->rows = Schema::create()
            ->setDescription('The rows of the textarea')
            ->setType([
                JsonSchema::INTEGER,
                JsonSchema::NULL,
            ]);
        $properties->cols = Schema::integer()
            ->setDescription('The cols of the textarea')
            ->setType([
                JsonSchema::INTEGER,
                JsonSchema::NULL,
            ]);
        $properties->maxlength = Schema::create()
            ->setDescription('The maxlength attribute of the textarea')
            ->setType([
                JsonSchema::INTEGER,
                JsonSchema::NULL,
            ]);
        $properties->minlength = Schema::create()
            ->setDescription('The minlength attribute of the textarea')
            ->setType([
                JsonSchema::INTEGER,
                JsonSchema::NULL,
            ]);
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
