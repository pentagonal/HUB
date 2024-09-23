<?php
declare(strict_types=1);

namespace Pentagonal\Hub\Interfaces;

use Pentagonal\Hub\Schema;
use Swaggest\JsonSchema\Structure\ObjectItemContract;

/**
 * @template TSchema of SchemaStructureInterface
 */
interface SchemaInterface
{
    /**
     * @var string BASE_ID_URL the base id uri schema
     */
    public const BASE_ID_URL = 'https://hub.pentagonal.org/';

    /**
     * Determine if processable classname
     *
     * @param class-string<TSchema> $className
     * @return bool
     */
    public static function processableInternalClassName(string $className): bool;

    /**
     * Determine schema url
     *
     * @param class-string<TSchema> $className
     * @return ?string
     */
    public static function determineInternalSchemaURL(string $className): ?string;

    /**
     * Determine schema path only - lowercase
     *
     * @param class-string<TSchema> $className
     * @return ?string
     */
    public static function determineInternalSchemaJsonPath(string $className): ?string;

    /**
     * @template TObject of ObjectItemContract
     *
     * Create schema from file
     *
     * @param string $file
     * @param class-string<TObject> $className
     * @return ?TObject
     */
    public static function createSchemaFromFile(string $file, string $className = Schema::class): ?ObjectItemContract;

    /**
     * Create schema
     *
     * Get schema from file
     * @param string $file the file of json
     * @param class-string<TSchema> $className the classname structure
     * @return TSchema
     */
    public static function createSchemaStructureFor(string $file, string $className) : ?SchemaStructureInterface;

    /**
     * Create schema
     *
     * @param string $className
     * @return SchemaStructureInterface
     */
    public static function createSchemaStructure(string $className): SchemaStructureInterface;

    /**
     * Create schema reference
     *
     * @param string $className
     * @return \Swaggest\JsonSchema\SchemaContract&ObjectItemContract
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public static function createSchemaReference(string $className) : ObjectItemContract;
}
