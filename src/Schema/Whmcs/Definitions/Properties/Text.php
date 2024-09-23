<?php
declare(strict_types=1);

namespace Pentagonal\Hub\Schema\Whmcs\Definitions\Properties;

use Swaggest\JsonSchema\JsonSchema;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

class Text extends ClassStructure
{
    /**
     * @var string $type should text
     */
    public string $type = 'text';

    /**
     * @var string $text The text of the definition
     */
    public string $text;

    /**
     * @noinspection PhpUndefinedFieldInspection
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $ownerSchema
            ->setType(JsonSchema::STRING)
            ->setDescription('The text of the definition')
            ->setAdditionalProperties(Schema::object()->setRef('#/definitions/htmlTagAttributes'))
            ->setRequired([
                'text',
            ]);
        $properties->type = Schema::string()
            ->setDescription('The type of the text')
            ->setDefault('text')
            ->setEnum([
                'text',
            ]);
        $properties->text = Schema::string()
            ->setDescription('The text of the definition')
            ->setType(JsonSchema::STRING);
    }
}
