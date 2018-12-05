<?php
declare(strict_types=1);

namespace ArsThanea\RemoteMediaBundle\MediaHandler\Imagine\Cache\Resolver;


use Liip\ImagineBundle\Imagine\Cache\Resolver\AwsS3Resolver as OriginalResolver;

final class AwsS3Resolver extends OriginalResolver
{
    /** @var string */
    private $filterSuffix;

    public function setFilterSuffix(string $filterSuffix): self
    {
        $this->filterSuffix = $filterSuffix;

        return $this;
    }

    public function remove(array $paths, array $filters): void
    {
        if (false === empty($paths)) {
            parent::remove($paths, $filters);

            return;
        }

        try {
            foreach ($filters as $filter) {
                $filterPattern = \sprintf('%s/%s', $filter, $this->filterSuffix);
                $this->storage->deleteMatchingObjects($this->bucket, $filterPattern);
            }
        } catch (\Exception $e) {
            $this->logError('The objects could not be deleted from Amazon S3.', [
                'filter' => \implode(', ', $filters),
                'bucket' => $this->bucket,
                'exception' => $e,
            ]);
        }
    }
}
