<?php

namespace Styde\Enlighten;

use Illuminate\Support\Str;
use Styde\Enlighten\Facades\Enlighten;
use Styde\Enlighten\Models\ExampleGroup;

class TestExampleGroup
{
    /**
     * @var TestRun
     */
    private $testRun;

    /**
     * @var string
     */
    private $className;

    /**
     * @var array
     */
    private $texts;

    /**
     * @var ExampleGroup|null
     */
    protected $exampleGroup = null;

    public function __construct(string $className, array $texts = [])
    {
        $this->testRun = TestRun::getInstance();
        $this->className = $className;
        $this->texts = $texts;
    }

    public function is(string $name): bool
    {
        return $this->className === $name;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function save(): ExampleGroup
    {
        if ($this->exampleGroup != null) {
            return $this->exampleGroup;
        }

        $run = $this->testRun->save();

        return $this->exampleGroup = ExampleGroup::create([
            'run_id' => $run->id,
            'class_name' => $this->getClassName(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'area' => Enlighten::getAreaSlug($this->getClassName()),
            'slug' => $this->getSlug()
        ]);
    }

    public function getTitle(): string
    {
        return $this->texts['title'] ?? $this->getDefaultTitle();
    }

    private function getDescription(): ?string
    {
        return $this->texts['description'] ?? null;
    }

    private function getSlug(): string
    {
        return Str::slug($this->getDefaultTitle());
    }

    public function getDefaultTitle(): string
    {
        $result = Str::of(class_basename($this->getClassName()));

        if ($result->endsWith('Test')) {
            $result = $result->substr(0, -4);
        }

        return $result->replaceMatches('@([A-Z])@', ' $1')->trim();
    }
}
