<?php

namespace Styde\Enlighten;

interface ExampleGroupBuilder
{
    public function is(string $name): bool;

    public function newExample(): ExampleBuilder;

    public function setClassName(string $className): ExampleGroupBuilder;

    public function setSlug(string $slug): ExampleGroupBuilder;

    public function setArea(string $area): ExampleGroupBuilder;

    public function setTitle(string $title): ExampleGroupBuilder;

    public function setDescription(?string $description): ExampleGroupBuilder;

    public function setOrderNum(int $orderNum): ExampleGroupBuilder;
}
