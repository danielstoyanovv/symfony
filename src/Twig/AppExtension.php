<?php

namespace App\Twig;

use App\Entity\File;
use PHPUnit\Framework\Constraint\DirectoryExists;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    /**
     * @var string
     */
    private $siteUrl;

    public function __construct(string $siteUrl)
    {
        $this->siteUrl = $siteUrl;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [$this, 'doSomething']),
            new TwigFunction('get_product_image', [$this, 'getProductImage']),
            new TwigFunction('get_file', [$this, 'getFile']),

        ];
    }

    public function doSomething($value)
    {
        // ...
    }

    /**
     * @param File $file
     * @return string
     */
    public function getFile(File $file): string
    {
        $fullFileUrl = $this->siteUrl . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;

        $fullFileUrl .= 'files' . DIRECTORY_SEPARATOR . $file->getName();

        return $fullFileUrl;
    }

    /**
     * @param File $file
     * @return string
     */
    public function getProductImage(File $file): string
    {
        $fullFileUrl = $this->siteUrl . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;

        $fullFileUrl .= 'images' . DIRECTORY_SEPARATOR . $file->getName();

        return $fullFileUrl;
    }
}
