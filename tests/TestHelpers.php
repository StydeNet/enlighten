<?php

namespace Tests;

trait TestHelpers
{
    protected function setConfig(array $config)
    {
        $this->app->config->set($config);
    }
}
