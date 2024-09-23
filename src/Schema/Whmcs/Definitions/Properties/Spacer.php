<?php
declare(strict_types=1);

namespace Pentagonal\Hub\Schema\Whmcs\Definitions\Properties;

use Swaggest\JsonSchema\JsonSchema;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

class Spacer extends ClassStructure
{
    /**
     * @var string $type should spacer
     */
    public string $type = 'spacer';

    /**
     * @noinspection PhpUndefinedFieldInspection
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $ownerSchema
            ->setType(JsonSchema::OBJECT)
            ->setDescription('The spacer attributes of the definition')
            ->setAdditionalProperties(Schema::object()->setRef('#/definitions/htmlTagAttributes'))
            ->setRequired([
                'type',
            ]);
        $properties->type = Schema::string()
            ->setDescription('The type of the spacer')
            ->setDefault('spacer')
            ->setEnum([
                'spacer',
            ]);
    }
}
