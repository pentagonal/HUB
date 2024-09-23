<?php
declare(strict_types=1);

namespace Pentagonal\Hub\Schema\Whmcs\Definitions\Properties;

use Swaggest\JsonSchema\JsonSchema;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

class Group extends ClassStructure
{
    /**
     * @var string $type should group
     */
    public string $type = 'group';

    /**
     * @var object $group The group of the definition
     */
    public object $group;

    /**
     * @noinspection PhpUndefinedFieldInspection
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $ownerSchema
            ->setType(JsonSchema::OBJECT)
            ->setDescription('The group attributes of the definition')
            ->setAdditionalProperties(Schema::object()->setRef('#/definitions/htmlTagAttributes'))
            ->setRequired([
                'group',
            ]);
        $properties->type = Schema::string()
            ->setDescription('The type of the group')
            ->setDefault('group')
            ->setEnum([
                'group',
            ]);
        $properties->group = Schema::object()
            ->setDescription('The group of the definition')
            ->setRef('#/definitions/settings');
    }
}
