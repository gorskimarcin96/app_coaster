<?php

declare(strict_types=1);

use PHPDevsr\Rector\Codeigniter4\Set\CodeigniterSetList;
use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Config\RectorConfig;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return RectorConfig::configure()
    ->withImportNames(removeUnusedImports: true)
    ->withSets(
        [
            LevelSetList::UP_TO_PHP_84,
            CodeigniterSetList::CODEIGNITER_45,
            SetList::CODE_QUALITY,
            SetList::TYPE_DECLARATION,
            SetList::DEAD_CODE,
        ],
    )
    ->withPaths([__DIR__ . '/app', __DIR__ . '/tests'])
    ->withParallel(120, 8, 10)
    ->withCache('/tmp/rector', FileCacheStorage::class)
    ->withSkip(
        [
            '*/Source/*',
            '*/Fixture/*',
            StringClassNameToClassConstantRector::class,
        ],
    );
