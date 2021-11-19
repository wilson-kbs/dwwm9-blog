<?php
// api/src/Serializer/MediaObjectNormalizer.php

namespace App\Serializer;

use App\Entity\Post;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Vich\UploaderBundle\Storage\StorageInterface;

final class PostImageNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
  use NormalizerAwareTrait;

  private const ALREADY_CALLED = 'POST_IMAGE_NORMALIZER_ALREADY_CALLED';

  public function __construct(private StorageInterface $storage)
  {
  }

  public function normalize($object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
  {
    $context[self::ALREADY_CALLED] = true;

    $object->contentUrl = $this->storage->resolveUri($object, 'img');

    return $this->normalizer->normalize($object, $format, $context);
  }

  public function supportsNormalization($data, ?string $format = null, array $context = []): bool
  {
    if (isset($context[self::ALREADY_CALLED])) {
      return false;
    }

    return $data instanceof Post;
  }
}
