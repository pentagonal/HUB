<?php
declare(strict_types=1);

namespace Pentagonal\Hub\Abstracts;

use Pentagonal\Hub\Interfaces\SchemaStructureInterface;
use Swaggest\JsonSchema\Structure\ClassStructure;

abstract class AbstractSchemaStructure extends ClassStructure implements SchemaStructureInterface
{
    /**
     * @var string $schema The schema uri of the schema
     */
    public string $schema = self::SCHEMA;

    /**
     * @var string $version The version of the schema
     */
    public string $version = '';

    /**
     * Get the schema uri of the schema
     *
     * @return string The schema uri of the schema
     */
    public function getSchema(): string
    {
        return $this->schema;
    }

    /**
     * Get the version of the schema
     *
     * @return string the version of the schema
     */
    public function getVersion(): string
    {
        return $this->version;
    }
}
