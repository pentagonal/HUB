<?php
declare(strict_types=1);

namespace Pentagonal\Hub\Schema\Whmcs\Definitions\Properties;

use Swaggest\JsonSchema\JsonSchema;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

class Hidden extends ClassStructure
{
    /**
     * @var string $type should hidden
     */
    public string $type = 'hidden';

    /**
     * @var string $value The value of the hidden input
     */
    public string $value;

    /**
     * @noinspection PhpUndefinedFieldInspection
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $ownerSchema
            ->setType(JsonSchema::STRING)
            ->setDescription('The hidden input of the definition')
            ->setAdditionalProperties(false)
            ->setRequired([
                'value',
            ]);
        $properties->type = Schema::string()
            ->setDescription('The type of the hidden input')
            ->setDefault('hidden')
            ->setEnum([
                'hidden',
            ]);
        $properties->value = Schema::string()
            ->setDescription('The value of the hidden input')
            ->setType(JsonSchema::STRING);
    }
}
