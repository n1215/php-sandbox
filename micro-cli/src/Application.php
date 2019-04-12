<?php
declare(strict_types=1);

namespace N1215\MicroCli;

/**
 * Class Application
 * @package N1215\MicroCli
 */
class Application
{
    /**
     * @var CliRouter
     */
    private $cliRouter;

    /**
     * @param array $commandMap
     */
    public function __construct(array $commandMap)
    {
        if (empty($commandMap)) {
            throw new \InvalidArgumentException('empty command map');
        }

        $this->cliRouter = new CliRouter($commandMap);
    }

    /**
     * @param array $argv
     */
    public function run(array $argv): void
    {
        $context = $this->cliRouter->match($argv);
        $context->getCommand()->handle(...$context->getInputs());
    }
}
