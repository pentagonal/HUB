<?php
declare(strict_types=1);

namespace Pentagonal\Hub\Schema\Whmcs;

use Pentagonal\Hub\Abstracts\AbstractSchemaStructure;
use Swaggest\JsonSchema\Constraint\Format;
use Swaggest\JsonSchema\JsonSchema;
use Swaggest\JsonSchema\Schema;
use function str_replace;

class Plugin extends AbstractSchemaStructure
{
    /**
     * @var string The version of the plugin
     */
    public const VERSION = '1.0.0';

    /**
     * @var string The schema uri of the plugin
     */
    public string $schema;

    /**
     * @var string The id of the plugin
     */
    public string $id = '';

    /**
     * @var string The name of the plugin
     */
    public string $name;

    /**
     * @var string $version the plugin version
     */
    public string $version = '';

    /**
     * @var ?string $menu_name the menu name
     */
    public ?string $menu_name = null;

    /**
     * @var ?string The namespace of the plugin
     */
    public ?string $namespace = null;

    /**
     * @var string The version of the plugin
     */
    public string $url = '';

    /**
     * @var string The author of the plugin
     */
    public string $author = '';

    /**
     * @var string The author url of the plugin
     */
    public string $author_url = '';

    /**
     * @var string The license of the plugin
     */
    public string $license = '';

    /**
     * @var string The license url of the plugin
     */
    public string $license_url = '';

    /**
     * @var bool Enable Page on addon plugin
     */
    public bool $enable_admin_page = false;

    public function __construct()
    {
        $this->id = \Pentagonal\Hub\Schema::determineInternalSchemaURL(self::class);
    }

    /**
     * @InheritDoc
     * @noinspection PhpUndefinedFieldInspection
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $ownerSchema->{Schema::PROP_ID} = \Pentagonal\Hub\Schema::determineInternalSchemaURL(self::class);
        $ownerSchema->version = self::VERSION;
        $ownerSchema
            ->setSchema(self::SCHEMA)
            //->setId(self::ID) // bug
            ->setTitle('Schema plugin for whmcs')
            ->setDescription('This schema to implement plugin for whmcs pentagonal addon')
            ->setType(JsonSchema::OBJECT)
            ->setRequired([
                'name',
                'namespace',
            ])
            ->addPropertyMapping('$schema', 'schema')
            ->addPropertyMapping(Schema::PROP_ID, 'id');
        $properties->schema = Schema::string()
            ->setDescription('The schema uri of the plugin')
            ->setFormat(Format::URI_REFERENCE);
        $properties->id = Schema::string()
            ->setDescription('The id of the plugin')
            ->setFormat(Format::URI_REFERENCE);
        $properties->name = Schema::string()
            ->setDescription('The name of the plugin');
        $properties->menu_name = Schema::string()
            ->setDescription('The menu name of the theme')
            ->setDefault('');
        $properties->version = Schema::string()
            ->setDescription('The version of the plugin');
        $properties->url = Schema::string()
            ->setDescription('The url of the plugin')
            ->setFormat(Format::URI_REFERENCE);
        $properties->author = Schema::string()
            ->setDescription('The author of the plugin');
        $properties->author_url = Schema::string()
            ->setDescription('The author url of the plugin')
            ->setFormat(Format::URI_REFERENCE);
        $properties->license = Schema::string()
            ->setDescription('The license of the plugin');
        $properties->license_url = Schema::string()
            ->setDescription('The license url of the plugin')
            ->setFormat(Format::URI_REFERENCE);
        $properties->enable_admin_page = Schema::boolean()
            ->setDescription('Enable Page on addon plugin')
            ->setDefault(false);
        $properties->namespace = Schema::string()
            ->setDescription('The namespace of the plugin class')
            ->setPattern(self::NAMESPACE_PATTERN);
    }

    /**
     * Get the id of the plugin
     *
     * @return string The id of the plugin
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get the name of the plugin
     *
     * @return string The name of the plugin
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getMenuName(): ?string
    {
        return $this->menu_name;
    }

    /**
     * Get the version of the plugin
     *
     * @return string The version of the plugin
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Get the author of the plugin
     *
     * @return string The author of the plugin
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Get the author url of the plugin
     *
     * @return string The author url of the plugin
     */
    public function getAuthorUrl(): string
    {
        return $this->author_url;
    }

    /**
     * Get the license of the plugin
     *
     * @return string The license of the plugin
     */
    public function getLicense(): string
    {
        return $this->license;
    }

    /**
     * Get the license url of the plugin
     *
     * @return string The license url of the plugin
     */
    public function getLicenseUrl(): string
    {
        return $this->license_url;
    }

    /**
     * Get namespace of the plugin
     *
     * @return ?string
     */
    public function getNamespace(): ?string
    {
        return $this->namespace ? str_replace('/', '\\', $this->namespace) : null;
    }

    /**
     * Check if the plugin has admin page
     *
     * @return bool
     */
    public function isEnableAdminPage(): bool
    {
        return $this->enable_admin_page;
    }

    /**
     * Get plugin classname
     *
     * @return string|null
     */
    public function getPluginClassName() : ?string
    {
        $namespace = $this->getNamespace();
        if (!$namespace) {
            return null;
        }
        return $namespace . '\\' . $this->getName();
    }
}
