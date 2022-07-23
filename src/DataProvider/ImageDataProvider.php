<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\DenormalizedIdentifiersAwareItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Image;
use Vich\UploaderBundle\Storage\StorageInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class ImageDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface, DenormalizedIdentifiersAwareItemDataProviderInterface 
{
    public function __construct(private CollectionDataProviderInterface $collectionDataProvider, private ItemDataProviderInterface $itemDataProvider, private CacheManager $cacheManager, private UploaderHelper $uploaderHelper)
    {
    }

    public function getCollection(string $resourceClass, ?string $operationName = null, array $context = [])
    {
        /** @var Image[] $images */
        $images = $this->collectionDataProvider->getCollection($resourceClass, $operationName, $context);

        foreach ($images as $image) {
            $image->setContentUrl($this->cacheManager->getBrowserPath($this->uploaderHelper->asset($image), 'thumbnail400x400'));
        }

        return $images;
    }

    public function getItem(string $resourceClass, $id, ?string $operationName = null, array $context = [])
    {
        /** @var Image|null $item */
        $item = $this->itemDataProvider->getItem($resourceClass, $id, $operationName, $context);

        if (!$item) {
            return null;
        }

        $item->setContentUrl($this->cacheManager->getBrowserPath($this->uploaderHelper->asset($item), 'widen_filter'));

        return $item;
    }

    public function supports(string $resourceClass, ?string $operationName = null, array $context = []): bool
    {
        return $resourceClass === Image::class;
    }
}
