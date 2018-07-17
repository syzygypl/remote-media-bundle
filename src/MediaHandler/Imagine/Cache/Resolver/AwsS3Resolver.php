<?php


namespace ArsThanea\RemoteMediaBundle\MediaHandler\Imagine\Cache\Resolver;


use Aws\S3\S3Client;

class AwsS3Resolver extends \Liip\ImagineBundle\Imagine\Cache\Resolver\AwsS3Resolver
{
    private $filterSuffix;

    /**
     * @param string $filterSuffix
     *
     * @return $this
     */
    public function setFilterSuffix($filterSuffix)
    {
        $this->filterSuffix = $filterSuffix;

        return $this;
    }

    public function remove(array $paths, array $filters)
    {
        if (!empty($paths)) {
            return parent::remove($paths, $filters);
        }


        try {
            foreach ($filters as $filter) {
                $this->storage->deleteMatchingObjects($this->bucket, sprintf("%s/%s", $filter, $this->filterSuffix));
            }
        } catch (\Exception $e) {
            $this->logError('The objects could not be deleted from Amazon S3.', array(
                'filter' => implode(', ', $filters),
                'bucket' => $this->bucket,
                'exception' => $e,
            ));
        }
    }

}
