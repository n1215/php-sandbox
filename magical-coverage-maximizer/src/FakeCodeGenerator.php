<?php
declare(strict_types=1);

namespace N1215\MagicalCoverageMaximizer;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\MethodGenerator;

/**
 * Class FakeCodeGenerator
 * @package N1215\MagicalCoverageMaximizer
 */
class FakeCodeGenerator
{
    private const CHUNK = 100;

    private const METHOD_LINE_CHUNK = 5000;

    /**
     * @param string $namespace
     * @param string $classPrefix
     * @param string $path
     * @param int $fileCount
     * @param int $lineCount
     */
    public function generate(string $namespace, string $classPrefix, string $path, int $fileCount, int $lineCount)
    {
        for ($i = 0; $i < $fileCount; $i++) {

            $chunkNumber = (int) floor($i / self::CHUNK);

            $chunkDirName = 'Chunk' . $chunkNumber;
            $classNameSpace = $namespace . '\\' . $chunkDirName;
            $className = $classPrefix . $i;
            $classFilePath = $path . DIRECTORY_SEPARATOR . $chunkDirName . DIRECTORY_SEPARATOR . $className . '.php';
            $this->generateClassFile($classNameSpace, $className, $classFilePath, $lineCount);
        }

        $this->generateManagerClassFile($namespace, 'Mcm', $classPrefix, $path, $fileCount);

    }

    private function generateManagerClassFile(string $namespace, string $className, string $fakeClassPrefix, string $path, int $fileCount): void
    {
        $chunk = self::CHUNK;
        $methodBody = <<< EOT
\$namespace = __NAMESPACE__;
\$classPrefix = '{$fakeClassPrefix}';
\$fileCount = {$fileCount};
\$chunkDirName = 'Chunk';
\$path = '{$path}';
for (\$i = 0; \$i < \$fileCount; \$i++) {

    \$chunkNumber = (int) floor(\$i / {$chunk});

    \$chunkDirName = 'Chunk' . \$chunkNumber;
    \$classNameSpace = \$namespace . '\\\\' . \$chunkDirName;
    \$className = \$classPrefix . \$i;
    \$classFilePath = \$path . DIRECTORY_SEPARATOR . \$chunkDirName . DIRECTORY_SEPARATOR . \$className . '.php';
    
    \$fullClassName = \$classNameSpace . '\\\\' . \$className;
    \$fullClassName::run();
}        
EOT;

        $file = new FileGenerator([
            'classes' => array(
                new ClassGenerator(
                    $className,  // name
                    $namespace,     // namespace
                    null,     // flags
                    null,     // extends
                    [],  // interfaces
                    [],  // properties
                    [
                        new MethodGenerator(
                            'run',                  // name
                            [],                  // parameters
                            MethodGenerator::FLAG_PUBLIC | MethodGenerator::FLAG_STATIC,                 // visibility
                            $methodBody  // body
                        ),
                    ]
                ),
            ),
        ]);

        $filePath = $path . DIRECTORY_SEPARATOR . $className. '.php';

        $file->setFilename($filePath);
        $file->write();

        echo 'generated ' . $filePath . ':' .  '(memory usage: ' . $this->convertMemoryUnit(memory_get_usage(true)) . ')' . PHP_EOL;
    }

    /**
     * @param string $namespace
     * @param string $className
     * @param string $filePath
     * @param int $lineCount
     */
    private function generateClassFile(string $namespace, string $className, string $filePath, int $lineCount)
    {

        $methodCount =  (int) ceil($lineCount / self::METHOD_LINE_CHUNK);
        $methodBody = <<<EOT
for (\$i = 0; \$i < {$methodCount}; \$i++) {
    \$methodName = "run\$i";
    self::\$methodName();
}
EOT;
        $methodGenerators = [];
        $methodGenerators[] = new MethodGenerator(
            'run',                  // name
            [],                  // parameters
            MethodGenerator::FLAG_PUBLIC | MethodGenerator::FLAG_STATIC,                 // visibility
            $methodBody  // body
        );

        for ($i = 0; $i < $methodCount; $i++) {
            $chunkedMethodBody = '';
            $chunkedLineCount = self::METHOD_LINE_CHUNK;
            for ($j = 0; $j < $chunkedLineCount; $j++) {
                $chunkedMethodBody .= "\$a=0;\n";
            }

            $methodGenerators[] = new MethodGenerator(
                'run' . $i,
                [],
                MethodGenerator::FLAG_PRIVATE | MethodGenerator::FLAG_STATIC,
                $chunkedMethodBody
            );
        }


        $file = new FileGenerator([
            'classes' => array(
                new ClassGenerator(
                    $className,  // name
                    $namespace,     // namespace
                    null,     // flags
                    null,     // extends
                    [],  // interfaces
                    [],  // properties
                    $methodGenerators
                ),
            ),
        ]);

        $dirPath = dirname($filePath);

        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0755, true);
        }

        $file->setFilename($filePath);
        $file->write();

        echo 'generated ' . $filePath . ':' .  '(memory usage: ' . $this->convertMemoryUnit(memory_get_usage(true)) . ')' . PHP_EOL;

        unset($file);
    }

    private function convertMemoryUnit(int $size): string
    {
        $units = ['b','kb','mb','gb','tb','pb'];
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$units[$i];
    }
}
