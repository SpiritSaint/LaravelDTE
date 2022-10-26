<?php

namespace SpiritSaint\LaravelDTE\Repositories;

class ConfigurationsRepository
{
    /**
     * Required XML version.
     * @var string
     */
    public static string $XML_version = '1.0';

    /**
     * Required encoding standard.
     * @var string
     */
    public static string $encoding = 'ISO-8859-1';

    /**
     * Required DTE version.
     * @var string
     */
    public static string $version = '1.0';
}