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
     * @param CliRouter $cliRouter
     */
    public function __construct(CliRouter $cliRouter)
    {
        $this->cliRouter = $cliRouter;
    }

    /**
     * @param array $argv
     */
    public function run(array $argv): void
    {
        // todo bootstrap
        $context = $this->cliRouter->match(Input::new($argv));
        $context->getCommand()->handle($context->getInput());
    }
}
