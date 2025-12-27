<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use CarlosChininin\AttachFile\EventListener\TablePrefixListener;
use CarlosChininin\AttachFile\Service\AttachFileService;
use CarlosChininin\AttachFile\Service\AttachFileUrlService;

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();
    $parameters->set('app.public_directory', '%kernel.project_dir%/public');
    $parameters->set('app.attach_file_directory', '/media');
    $parameters->set('app.attach_file_safe', 0);
    $parameters->set('app.attach_file_table_prefix', '');

    $services->defaults()
        ->private()
        ->autowire()
        ->autoconfigure();

    $services->load('CarlosChininin\\AttachFile\\', '../../../src/*')
        ->exclude([
            '../../../src/{DependencyInjection,Model,Resources,AttachFileBundle.php}',
        ]);

    $services->load('CarlosChininin\\AttachFile\\Api\\', '../../../src/Api/')
        ->tag('controller.service_arguments');

    $services->set(AttachFileService::class)
        ->args([
            '$publicDirectory' => '%app.public_directory%',
            '$attachFileDirectory' => '%app.attach_file_directory%',
            '$isSafe' => '%app.attach_file_safe%',
        ]);

    $services->set(AttachFileUrlService::class)
        ->args([service('url_helper')]);

    $services->set('table.listener.prefix', TablePrefixListener::class)
        ->call('setPrefix', ['%app.attach_file_table_prefix%'])
        ->tag('doctrine.event_listener', ['event' => 'loadClassMetadata', 'method' => 'loadClassMetadata']);
};
