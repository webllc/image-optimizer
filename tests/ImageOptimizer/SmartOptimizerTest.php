<?php
declare(strict_types=1);

namespace webllc\ImageOptimizer;


use webllc\ImageOptimizer\TypeGuesser\TypeGuesser;
use PHPUnit\Framework\TestCase;

class SmartOptimizerTest extends TestCase
{
    const SUPPORTED_TYPE = 'png';
    const UNSUPPORTED_TYPE = 'gif';

    const SUPPORTED_FILEPATH = 'somefilepath';
    const UNSUPPORTED_FILEPATH = 'unsupportedFilepath';

    /**
     * @var SmartOptimizer
     */
    private $optimizer;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $internalOptimizer;

    protected function setUp()
    {
        $this->internalOptimizer = $this->createMock('webllc\\ImageOptimizer\\Optimizer');

        $this->optimizer = new SmartOptimizer([
            self::SUPPORTED_TYPE => $this->internalOptimizer,
        ], new SmartOptimizerTest_TypeGuesser([
            self::SUPPORTED_FILEPATH => self::SUPPORTED_TYPE,
            self::UNSUPPORTED_FILEPATH => self::UNSUPPORTED_TYPE,
        ]));
    }

    /**
     * @test
     */
    public function givenSupportedFilepath_executeInternalOptimizer()
    {
        //given

        $filepath = self::SUPPORTED_FILEPATH;

        $this->internalOptimizer->expects($this->once())
            ->method('optimize')
            ->with($filepath);

        //when

        $this->optimizer->optimize($filepath);
    }

    /**
     * @test
     * @expectedException webllc\ImageOptimizer\Exception\Exception
     */
    public function givenUnsupportedFilepath_throwException()
    {
        //given

        $filepath = self::UNSUPPORTED_FILEPATH;

        $this->internalOptimizer->expects($this->never())
            ->method('optimize');

        //when

        $this->optimizer->optimize($filepath);
    }
}

class SmartOptimizerTest_TypeGuesser implements TypeGuesser
{
    private $filepathToType;

    public function __construct(array $filepathToType)
    {
        $this->filepathToType = $filepathToType;
    }

    public function guess(string $filepath): string
    {
        return isset($this->filepathToType[$filepath]) ? $this->filepathToType[$filepath] : 'unknown';
    }
}