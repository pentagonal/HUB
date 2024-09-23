<?php
declare(strict_types=1);

namespace Pentagonal\Hub;

use Pentagonal\Hub\Exceptions\InvalidArgumentClassNameException;
use Pentagonal\Hub\Exceptions\UnexpectedValueException;
use Pentagonal\Hub\Interfaces\SchemaInterface;
use Pentagonal\Hub\Interfaces\SchemaStructureInterface;
use RuntimeException;
use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\Structure\ClassStructureContract;
use Swaggest\JsonSchema\Structure\ObjectItemContract;
use Throwable;
use function class_exists;
use function file_get_contents;
use function file_put_contents;
use function filemtime;
use function is_dir;
use function is_file;
use function is_object;
use function is_subclass_of;
use function is_writable;
use function json_decode;
use function mkdir;
use function preg_match;
use function restore_error_handler;
use function set_error_handler;
use function sha1;
use function sprintf;
use function str_replace;
use function strlen;
use function strpos;
use function strtolower;
use function substr;
use function sys_get_temp_dir;
use function time;
use function unlink;
use const E_USER_WARNING;

final class Schema implements SchemaInterface
{
    /**
     * @var string CACHE_PREFIX the cache prefix
     */
    public const CACHE_PREFIX = 'pentagonal_schema_cache';

    /**
     * @var string CACHE_EXTENSION the cache extension
     */
    public const CACHE_EXTENSION = '.cache';

    /**
     * @var string SCHEMA_NAMESPACE the schema namespace
     */
    public const SCHEMA_NAMESPACE = __NAMESPACE__ . '\\Schema\\';

    /**
     * @var array<string, \Swaggest\JsonSchema\SchemaContract&ClassStructureContract>
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    private static array $refSchema = [];

    /**
     * @var Context $contextOption the context option
     */
    private static Context $contextOption;

    /**
     * @inheritDoc
     */
    public static function processableInternalClassName(string $className): bool
    {
        return  !(!class_exists($className)
            || strpos($className, '@') !== false
            || strpos($className, self::SCHEMA_NAMESPACE) !== 0
            || !is_subclass_of($className, SchemaStructureInterface::class, true)
        );
    }

    /**
     * @inheritDoc
     */
    public static function determineInternalSchemaJsonPath(string $className): ?string
    {
        if (!self::processableInternalClassName($className)) {
            return null;
        }
        $baseName = substr($className, strlen(__NAMESPACE__) + 1);
        return str_replace('\\', '/', strtolower($baseName)) . '.json';
    }

    /**
     * @inheritDoc
     */
    public static function determineInternalSchemaURL(string $className): ?string
    {
        $path = self::determineInternalSchemaJsonPath($className);
        return $path ? self::BASE_ID_URL . $path : null;
    }

    /**
     * @inheritDoc
     */
    public static function createSchemaStructure(string $className): SchemaStructureInterface
    {
        self::$contextOption ??= new Context();
        self::$contextOption->skipValidation = true;

        /**
         * @var \Swaggest\JsonSchema\Structure\ObjectItemContract&SchemaStructureInterface $contract
         * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
         */
        $contract = self::createSchemaReference($className)->makeObjectItem(self::$contextOption);
        return $contract;
    }

    /**
     * @inheritDoc
     */
    public static function createSchemaReference(string $className)
    {
        $lowerClassName = strtolower(trim($className, '\\'));
        if (isset(self::$refSchema[$lowerClassName])) {
            return clone self::$refSchema[$lowerClassName];
        }
        if (!is_subclass_of($className, SchemaStructureInterface::class, true)) {
            throw new InvalidArgumentClassNameException(
                'Invalid Schema Structure Class'
            );
        }
        self::$refSchema[$lowerClassName] = $className::schema();
        return clone self::$refSchema[$lowerClassName];
    }

    /**
     * @inheritDoc
     * @throws UnexpectedValueException
     */
    public static function createSchemaFromFile(string $file, string $className = Schema::class): ?ObjectItemContract
    {
        /** @noinspection PhpRedundantOptionalArgumentInspection */
        if ($className === Schema::class || is_subclass_of($className, ClassStructureContract::class, true)) {
            try {
                return $className::import(self::readJsonSchemaFile($file));
            } catch (Throwable $e) {
                throw new UnexpectedValueException($e->getMessage(), $e->getCode(), $e);
            }
        }
        throw new UnexpectedValueException(sprintf('Invalid Schema Class: %s', $className), E_USER_WARNING);
    }

    /**
     * @inheritDoc
     * @throws UnexpectedValueException
     */
    public static function createSchemaStructureFor(string $file, string $className) : ?SchemaStructureInterface
    {
        if (!is_subclass_of($className, SchemaStructureInterface::class)) {
            throw new InvalidArgumentClassNameException(sprintf('Invalid Schema Class: %s', $className));
        }
        if (!file_exists($file)) {
            return null;
        }

        $file = realpath($file)?:$file;
        $refSchema = self::createSchemaReference($className);
        $schema = self::createSchemaFromFile($file, $className);
        if (!$schema instanceof SchemaStructureInterface || ! $schema instanceof $className) {
            throw new InvalidArgumentClassNameException('Invalid Schema');
        }
        self::$contextOption ??= new Context();
        self::$contextOption->skipValidation = true;
        try {
            $refSchema->in($schema->jsonSerialize(), self::$contextOption);
        } catch (Throwable $e) {
            throw new UnexpectedValueException($e->getMessage(), $e->getCode(), $e);
        }
        return $schema;
    }

    /**
     * Read json file
     *
     * @param string $file
     * @return ?array
     * @throws UnexpectedValueException
     */
    protected static function readJsonSchemaFile(string $file) : ?object
    {
        set_error_handler(
            static function ($errno, $errStr) {
                throw new RuntimeException($errStr, $errno);
            },
            E_USER_WARNING
        );
        $originalFile = $file;
        $isRemote = preg_match('/^(http|ftp)s?:\/\//', $file);
        // cache directory
        $tempDir = sys_get_temp_dir() . '/' . self::CACHE_PREFIX;
        $remoteFile = $tempDir . '/' . sha1($file) . self::CACHE_EXTENSION;
        $isCached = false;
        if ($isRemote) {
            if (!is_dir($tempDir)) {
                set_error_handler(function () {
                });
                mkdir($tempDir, 0777, true);
                restore_error_handler();
            }
            if (is_file($remoteFile) && filemtime($remoteFile) > (time() - 3600)) {
                $isCached = true;
                $file = $remoteFile;
            }
        }
        try {
            $content = file_get_contents($file);
            if (!$isCached && $isRemote && is_dir($tempDir) && is_writable($tempDir)) {
                if (is_file($remoteFile)) {
                    unlink($remoteFile);
                }
                file_put_contents($remoteFile, $content === false ? 'false' : $content);
            }
            if ($content === false || $content === 'false') {
                throw new UnexpectedValueException(sprintf('Failed to read file: %s', $originalFile), E_USER_WARNING);
            }
        } finally {
            restore_error_handler();
        }

        $json = json_decode($content, false);
        if (!is_object($json)) {
            throw new UnexpectedValueException(sprintf('Invalid JSON Schema File: %s', $content), E_USER_WARNING);
        }
        return $json;
    }
}
