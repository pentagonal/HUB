<?php
declare(strict_types=1);

namespace Pentagonal\Hub\Schema\Whmcs\Definitions\Properties;

use Swaggest\JsonSchema\JsonSchema;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

class Title extends ClassStructure
{
    /**
     * @var string $type should title
     */
    public string $type = 'title';

    /**
     * @var string $title The title of the definition
     */
    public string $title;

    /**
     * @var string $tag The tag of the title
     */
    public string $tag = 'h2';

    /** @noinspection PhpUndefinedFieldInspection */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $ownerSchema
            ->setType(JsonSchema::STRING)
            ->setDescription('The title of the definition')
            ->setAdditionalProperties(Schema::object()->setRef('#/definitions/htmlTagAttributes'))
            ->setRequired([
                'title',
            ]);
        $properties->type = Schema::string()
            ->setDescription('The type of the title')
            ->setDefault('title')
            ->setEnum([
                'title',
            ]);
        $properties->title = Schema::string()
            ->setDescription('The title of the definition')
            ->setType(JsonSchema::STRING);
        $properties->tag = Schema::string()
            ->setDescription('The tag of the title')
            ->setType(JsonSchema::STRING)
            ->setEnum([
                'h1',
                'h2',
                'h3',
                'h4',
                'h5',
                'h6',
                'div',
            ])
            ->setDefault('h2');
    }
}
