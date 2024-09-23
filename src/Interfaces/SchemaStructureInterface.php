<?php
declare(strict_types=1);

namespace Pentagonal\Hub\Interfaces;

use Swaggest\JsonSchema\Structure\ClassStructureContract;

interface SchemaStructureInterface extends ClassStructureContract
{
    /**
     * @var int SCHEMA_VERSION the schema version, only support draft-07
     */
    public const SCHEMA_VERSION = 7;

    /**
     * The schema - draft 7
     * @var string SCHEMA The schema uri of the schema
     */
    public const SCHEMA = 'http://json-schema.org/draft-07/schema';

    /**
     * @var string NAMESPACE_PATTERN The namespace pattern
     */
    public const NAMESPACE_PATTERN =
        '^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*([\\\\/][a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)*$';


    /**
     * Create class structure contract
     *
     * @return \Swaggest\JsonSchema\SchemaContract
     * @noinspection PhpFullyQualifiedNameUsageInspection
     * @noinspection PhpMissingReturnTypeInspection
     */
    public static function schema();

    /**
     * Get the schema uri of the schema
     *
     * @return string The schema uri of the schema
     */
    public function getSchema(): string;

    /**
     * Get the version of the schema
     *
     * @return string the version of the schema
     */
    public function getVersion(): string;
}
