<?php
declare(strict_types=1);

namespace ArsThanea\RemoteMediaBundle\MediaHandler\Imagine\DataLoader;


use Liip\ImagineBundle\Binary\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ChainedDataLoader implements LoaderInterface
{
    /** @var LoaderInterface[] */
    private $loaders;

    public function addLoader(LoaderInterface $loader): void
    {
        $this->loaders[] = $loader;
    }

    public function find($path)
    {
        $exception = null;

        if (false !== \strpos($path, ' ')) {
            $path = \urlencode($path);
        }

        foreach ($this->loaders as $loader) {
            try {
                return $loader->find($path);
            } catch (NotFoundHttpException $e) {
                $exception = $e;
            }
        }

        throw $exception;
    }
}
