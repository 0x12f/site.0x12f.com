<?php declare(strict_types=1);

/** @var \Slim\Container $container */

// файл для подключения плагинов
// использя ссылку на plugins необходимо зарегистрировать каждый плагин
$plugins = $container->get('plugin');

// SearchOptimizationPlugin
$plugins->register(new \Plugin\SearchOptimization\SearchOptimizationPlugin($container));

// ClearCache
$plugins->register(new \Plugin\ClearCache\ClearCachePlugin($container));
