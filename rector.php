<?php

// rector.php
declare(strict_types=1);

use Rector\Arguments\ValueObject\ReplaceFuncCallArgumentDefaultValue;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Rector\Arguments\Rector\ClassMethod\ArgumentAdderRector;
use Rector\Arguments\Rector\FuncCall\FunctionArgumentDefaultValueReplacerRector;
use Rector\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector;

return static function (ContainerConfigurator $containerConfigurator): void {
// here we can define, what sets of rules will be applied
// tip: use "SetList" class to autocomplete sets
    $containerConfigurator->import(SetList::CODE_QUALITY);
    $containerConfigurator->import(SetList::DEAD_CODE);
    $containerConfigurator->import(SetList::TYPE_DECLARATION);
    $containerConfigurator->import(SetList::TYPE_DECLARATION_STRICT);
    $containerConfigurator->import(SetList::PSR_4);
    $containerConfigurator->import(SetList::NAMING);
    $containerConfigurator->import(SetList::PHP_74);
    $containerConfigurator->import(SetList::CODING_STYLE);


    $services = $containerConfigurator->services();
    $services->set(ReturnTypeDeclarationRector::class);

// register single rule
    //$services = $containerConfigurator->services();
    //$services->set(TypedPropertyRector::class);
  //  $services->set(FunctionArgumentDefaultValueReplacerRector::class)->configure([new ReplaceFuncCallArgumentDefaultValue('version_compare', 2, 'gte', 'ge')]);

};
