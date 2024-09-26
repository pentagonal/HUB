#!/usr/bin/env php
<?php
declare(strict_types=1);

use Pentagonal\Hub\Interfaces\SchemaInterface;
use Pentagonal\Hub\Schema;
use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\JsonSchema;
use Swaggest\JsonSchema\SchemaExporter;

if (php_sapi_name() !== 'cli') {
    die('This script can only be run from the command line.');
}
// point cwd
chdir(dirname(__DIR__));
$args = [];
foreach ($argv as $arg) {
    if (strpos($arg, '--') === 0) {
        $arg = explode('=', $arg, 2);
        $args[substr($arg[0], 2)] = $arg[1] ?? true;
        continue;
    }
    if (strpos($arg, '-') === 0) {
        $arg = str_split(substr($arg, 1));
        foreach ($arg as $a) {
            $args[$a] = true;
        }
    }
}

$targetDir = $args['target'] ?? null;
if (!is_string($targetDir)) {
    echo "\033[31mError: --target is required\033[0m\n";
    exit(1);
}

$targetDir = dirname(__DIR__) . '/' . $targetDir;

if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

if (!is_dir($targetDir)) {
    echo "\033[31mError: --target is not a directory\033[0m\n";
    exit(1);
}

if (!file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    echo "\033[31mError: vendor/autoload.php not found\033[0m\n";
    exit(1);
}

require dirname(__DIR__) . '/vendor/autoload.php';

$reflection = new ReflectionClass(Schema::class);
$nameSpace = $reflection->getNamespaceName();
$sourceDirectory = dirname($reflection->getFileName());
$schemaDirectory = $sourceDirectory . '/Schema';

if (!is_dir($schemaDirectory)) {
    echo "\033[31mError: Schema directory not found\033[0m\n";
    exit(1);
}
$content = new Context();
$content->skipValidation = true;
$classNameListUri = [];
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($schemaDirectory));
// put jekyll
file_put_contents($targetDir . '/.nojekyll', '');
foreach ($iterator as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') {
        continue;
    }
    $className = substr($file->getPathname(), strlen($sourceDirectory) + 1, -4);
    $className = str_replace(DIRECTORY_SEPARATOR, '\\', $className);
    $className = $nameSpace . '\\' . $className;
    $target = Schema::determineInternalSchemaJsonPath($className);
    if (!$target) {
        continue;
    }
    try {
        $jsonSchema = Schema::createSchemaReference($className);
    } catch (Throwable $e) {
        echo "\033[31mError: {$e->getMessage()}\033[0m\n";
        exit(1);
    }
    $targetPath = $targetDir . '/' . $target;
    if (!is_dir(dirname($targetPath))) {
        mkdir(dirname($targetPath), 0755, true);
    }
    if (!is_dir(dirname($targetPath))) {
        echo "\033[31mError: Cannot create directory for {$target}\033[0m\n";
        exit(1);
    }
    if ($jsonSchema instanceof SchemaExporter) {
        $jsonSchema = $jsonSchema->exportSchema();
    }
    if (!$jsonSchema instanceof JsonSchema) {
        echo "\033[31mError: {$className} is not a valid schema\033[0m\n";
        exit(1);
    }
    file_put_contents($targetDir . '/' . $target, json_encode($jsonSchema, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
    $classNameListUri[$target] = [
        'title' => $jsonSchema->title ?? $target,
        'version' => $jsonSchema->version ?? '-',
        'description' => $jsonSchema->description ?? '-',
        'data' => json_encode($jsonSchema, JSON_UNESCAPED_SLASHES)
    ];
}
$total = 0;
$htmlSchemaList = '';
$htmlTabList = '';
$selectedCSS = [];
foreach ($classNameListUri as $target => $data) {
    unset($classNameListUri[$target]);
    $total++;
    $title = htmlspecialchars($data['title'], ENT_QUOTES);
    $target = htmlspecialchars("$target", ENT_QUOTES);
    $version = htmlspecialchars($data['version'], ENT_QUOTES);
    $description = htmlspecialchars($data['description'], ENT_QUOTES);
    $target = htmlspecialchars($target, ENT_QUOTES);
    $id = 'schema-' . $total;
    $radioId = $id . '-radio';
    $selectedCSS[] .= ":has(input[id={$radioId}]:checked) label[for=$radioId]";
    $data = htmlspecialchars($data['data'], ENT_QUOTES);
    $htmlTabList .= <<<HTML

        <label for="{$radioId}">{$title}</label>
HTML;
    $htmlSchemaList .= <<<HTML
        <div class="tab-wrapper">
            <div class="tab-content">
                <input type="radio" name="tab" id="{$radioId}">
                <h2>{$title}</h2>
                <table>
                    <tr>
                        <td>Version</td>
                        <td>{$version}</td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td>{$description}</td>   
                    </tr>
                    <tr>
                        <td>Filename</td>
                        <td>{$target}</td>
                    </tr>    
                </table>
                <p>
                    <a href="{$target}" target="_blank" class="button">View Schema</a>
                    <span class="preview-schema button" data-json="$data">View Schema</span>
                </p>
            </div>
        </div>
HTML;
}
$selectedCSS = implode(",\n", $selectedCSS);
$htmlIndex = <<<HTML
<!DOCTYPE html>
<html lang="en" class="nojs">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noodp, noydir">
    <title>Schema List</title>
    <style>
    /*# sourceURL=style.css */
body:not(:has(input[type="radio"]:checked)) nav label:first-child,
{$selectedCSS} {
    background: rgba(0,0,0,.1);
}
:root {
    --tab-list-height: 200px;
}
html {
    font-family: -apple-system, BlinkMacSystemFont,
        Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans,
        Liberation Sans, sans-serif, Apple Color Emoji,
        Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji;
    line-height: 1.5;
    color: #333;
    font-size: 16px;
}

html, body {
    margin: 0;
    padding: 0;
}

*,*:before,*:after {
    box-sizing: border-box;
}

p {
    margin: 0 0 .5rem;
}
h1, h2 {
    margin: 0 0 .3rem;
}
h1 {
    font-size: 2rem;
    padding: .5rem;
}
h2 {
    font-size:1.2rem;
}
body {
    background: #f1f1f1;
}
.page {
    display: flex;
    flex-direction: row;
}
.page {
    min-height: 100vh;
    position:relative;
    height: 100%;
}
.left-menu {
    z-index:99;
    flex: 0 0 300px;
    position: relative;
    top:0;
    left:0;
    background: #227480;
    color: #fff;
    margin-left:0;
    transition: margin-left ease .3s;
    height: 100vh;
    overflow-x: inherit;
    overflow-y: auto;
    scroll-behavior: smooth;
    scrollbar-width: none;
}
.top-left-menu {
    display: flex;
    height: 3rem;
    background-color: #1b4d55;
    color: #ffffff;
    align-items: center;
    flex-direction: column;
    justify-content: center;
    position: sticky;
    top:0;
}
.top-left-menu h1 {
    font-size: 1.1rem;
    font-weight: lighter;
    margin:0;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.schema-list {
    z-index:88;
    display: flex;
    flex-direction: column;
    flex: 0 1 100%;
    height: 100vh;
    overflow: auto;
    padding: 0;
}
.left-menu label {
    display: block;
    padding: .8rem .4rem;
    text-decoration:none;
    cursor: pointer;
}
input[type="radio"],
input[type="checkbox"],
.schema-list .tab-wrapper {
    display:none;
}
.schema-list .tab-wrapper:has(input[type="radio"]:checked),
.schema-list:not(:has(input[type="radio"]:checked)) .tab-wrapper:first-child {
    display: flex;
    flex-direction: column;
    flex: 0 0 100%;
}
.tab-list {
    padding: 1rem;
}
.nav-header {
    position: sticky;
    top: 0;
    background-color:#227480;
    display: flex;
    align-items: center;
}
.nav-header label {
    cursor: pointer;
    display: flex;
    width: 2rem;
    height: 2rem;
    margin: .5rem 1rem;
    font-size:0;
    outline:0;
    position: relative;
    background: transparent;
    align-content: center;
    align-items: center;
    flex-direction: column;
}
.nav-header label::after,
.nav-header label::before {
    content: '';
    display: block;
    height: 4px;
    width: 2rem;
    background: #fff;
    position: relative;
    transform: rotate(45deg);
    top: .85rem;
    transition: all ease .3s;
}
.nav-header label::after {
    transform: rotate(-45deg);
    top:.6rem;
}
.left-menu:has(:checked) {
    margin-left:-300px;
}
@media (max-width: 640px) {
    .tab-list {
        transition: all ease .3s;
        margin-left:0;
    }
    body:not(:has(.left-menu :checked)) .tab-list {
        margin-left: -300px;
    }
}
:has(.left-menu :checked) .nav-header label::after,
:has(.left-menu :checked) .nav-header label::before {
    transform: rotate(0);
    top: 20%;
}
:has(.left-menu :checked) .nav-header label::after {
    top: 50%;
}
.nojs .preview-schema {
    display:none;
}
.preview-schema {
    margin-left: 1rem;
}
table {
    width: 100%;
    margin: 1rem 0;
    padding: 0;
    border-collapse: collapse;
    font-size: .9rem;
    border-top: 1px solid rgba(0,0,0,.1);
    border-bottom: 1px solid rgba(0,0,0,.1);
}
tr td {
    padding: .4rem;
}
tr td:first-child {
    font-weight: bold;
    padding-left:0;
    width: 100px;
}
.button {
    text-decoration:none;
    color: #444;
    font-size: .8rem;
    padding: .4rem .6rem;
    border: 2px solid #444;
    display: inline-block;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-radius: .25rem;
    transition: all ease .1s;
    cursor: pointer;
    user-select: none;
}
.button:hover,
.button:focus {
    border-color: #1b4d55;
    color: #1b4d55;
}
pre.json-schema {
    padding: 1rem;
    margin: 1rem 0;
    border-radius: .25rem;
    overflow: auto;
    white-space: pre-wrap;
    font-size: .8rem;
    font-family: SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;
    min-height: 300px;
    height: calc(100vh - var(--tab-list-height) - 7.5rem);
    /* monokai background */
    background: #272822;
    color: #f8f8f2;
    scroll-behavior: smooth;
    scrollbar-width: thin;
    border: 1px solid #333;
}
</style>
</head>
<body>
<div class="page">
    <div class="left-menu">
        <div class="top-left-menu">
            <h1>Schema List</h1>
        </div>
        <input type="checkbox" id="sidebar-checkbox">
        <nav>
            $htmlTabList
        </nav>
    </div>
    <div class="schema-list">
        <div class="nav-header">
            <label for="sidebar-checkbox">Menu</label>
        </div>
        <div class="tab-list">
            $htmlSchemaList
        </div>
    </div>
</div>
<script>
    (function (w, d) {
        d.documentElement.classList.remove('nojs');
        if (w.innerWidth < 900) {
            d.getElementById('sidebar-checkbox').checked = true;
        }
        d.querySelectorAll('[data-json')
            .forEach(function (el) {
                let json = JSON.parse(el.getAttribute('data-json'));
                el.removeAttribute('data-json');
                let pre = d.createElement('pre');
                pre.textContent = JSON.stringify(json, null, 2);
                pre.style.display = 'none';
                pre.classList.add('json-schema');
                json = null;
                const tabContent = el.closest('.tab-content');
                const tabWrapper = el.closest('.tab-wrapper');
                const height = function () {
                    let computed = w.getComputedStyle(tabContent);
                    let tabContentHeight = parseInt(computed.height, 10);
                        tabContentHeight+= parseInt(computed.paddingTop, 10);
                        tabContentHeight+= parseInt(computed.paddingBottom, 10);
                        tabContentHeight+= parseInt(computed.marginTop, 10);
                        tabContentHeight+= parseInt(computed.marginBottom, 10);
                    tabWrapper.style.setProperty('--tab-list-height', tabContentHeight + 'px');
                };
                tabWrapper.appendChild(pre);
                window.addEventListener('resize', height);
                el.addEventListener('click', function () {
                    if (el.classList.contains('active')) {
                        el.classList.remove('active');
                        el.innerHTML = 'View Schema';
                        pre.style.display = 'none';
                    } else {
                        el.classList.add('active');
                        pre.style.display = 'block';
                        el.innerHTML = 'Hide Schema';
                        height();
                    }
                });
            });
    })(window, document);
</script>
</body>
</html>
HTML;

file_put_contents($targetDir . '/index.html', $htmlIndex);
// make custom domain work
$url = SchemaInterface::BASE_ID_URL;
$host = parse_url($url, PHP_URL_HOST);
file_put_contents($targetDir . '/CNAME', $host);
echo "\033[32mSuccess: {$total} schema exported to {$targetDir}\033[0m\n";
exit(0);
