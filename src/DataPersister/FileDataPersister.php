<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\File;
use App\Entity\Product;
use Psr\Log\InvalidArgumentException;
use Doctrine\ORM\EntityManagerInterface;

class FileDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof File;
    }

    public function persist($data, array $context = []): void
    {
        $product = $this->validateProductName($data);
        $this->validateContent($data);
        $this->validateName($data);

        $originalName = $data->getName();
        $imageName = pathinfo($originalName, PATHINFO_FILENAME);
        $uniqueImageName = $imageName . '-' . uniqid() . '.' . explode('.', $originalName)[1];
        $fullImagePath = 'uploads' . DIRECTORY_SEPARATOR .  'images' . DIRECTORY_SEPARATOR . $uniqueImageName;
        file_put_contents($fullImagePath, base64_decode($data->content, true));

        $data
            ->setName($uniqueImageName)
            ->setOriginalName($originalName)
            ->setProduct($product)
            ->setMime(mime_content_type($fullImagePath))
            ->setSize(filesize($fullImagePath))
            ->setType("image")
        ;

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }


    /**
     * @param File $data
     * @return mixed|object
     */
    private function validateProductName(File $data)
    {
        if (empty($data->productName)) {
            throw new InvalidArgumentException("'productName' is required field");
        } else {
            if (!$checkProductExists = $this->entityManager->getRepository(Product::class)->findOneBy(['name' => $data->productName])) {
                throw new InvalidArgumentException(
                    sprintf("Product %s did not exists", $data->productName)
                );
            }

            return $checkProductExists;
        }
    }

    /**
     * @param File $data
     * @return void
     */
    private function validateContent(File $data): void
    {
        if (empty($data->content)) {
            throw new InvalidArgumentException("'content' is required field");
        }
    }

    /**
     * @param File $data
     * @return void
     */
    private function validateName(File $data): void
    {
        $imageNameParts = explode('.', $data->getName());

        if (count($imageNameParts) == 1) {
            throw new InvalidArgumentException(
                sprintf("Image %s is not a valid image name, example: 'test.jpg'", $data->getName())
            );
        }

        if (!in_array(
            strtolower($imageNameParts[1]),
            [
            'jpeg',
            'jpg',
            'png',
            'gif',
            'tiff'
            ],
            true
        )) {
            throw new InvalidArgumentException(
                sprintf(
                    "Image format %s is not allowed, allowed are: 'jpeg', 'jpg', 'png', 'gif', 'tiff'",
                    strtolower($imageNameParts[1])
                )
            );
        }
    }
}
