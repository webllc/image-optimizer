<?php
declare(strict_types=1);

namespace webllc\ImageOptimizer\TypeGuesser;


class SmartTypeGuesserTest extends AbstractTypeGuesserTest
{
    protected function createTypeGuesser(): TypeGuesser
    {
        return new SmartTypeGuesser();
    }

    public function validImageFileProvider()
    {
        $images = parent::validImageFileProvider();
        $images[] =  [
            __DIR__.'/../Resources/sample.svg',
            TypeGuesser::TYPE_SVG,
        ];

        return $images;
    }
}
 