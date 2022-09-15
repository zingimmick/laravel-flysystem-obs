<?php

declare(strict_types=1);

namespace Zing\LaravelFlysystem\Obs;

use GuzzleHttp\Psr7\Uri;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\FilesystemOperator;
use Obs\ObsClient;
use Zing\Flysystem\Obs\ObsAdapter as Adapter;

class ObsAdapter extends FilesystemAdapter
{
    /**
     * @var \Zing\Flysystem\Obs\ObsAdapter
     */
    protected $adapter;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(
        FilesystemOperator $driver,
        Adapter $adapter,
        array $config,
        protected ObsClient $obsClient
    ) {
        parent::__construct($driver, $adapter, $config);
    }

    /**
     * Get the URL for the file at the given path.
     *
     * @param string $path
     */
    public function url($path): string
    {
        if (isset($this->config['url'])) {
            return $this->concatPathToUrl($this->config['url'], $this->prefixer->prefixPath($path));
        }

        $uri = new Uri($this->signUrl($this->prefixer->prefixPath($path), 0, []));

        return (string) $uri->withQuery('');
    }

    /**
     * Determine if temporary URLs can be generated.
     */
    public function providesTemporaryUrls(): bool
    {
        return true;
    }

    /**
     * Get a temporary URL for the file at the given path.
     *
     * @param string $path
     * @param \DateTimeInterface $expiration
     * @param array<string, mixed> $options
     */
    public function temporaryUrl($path, $expiration, array $options = []): string
    {
        $uri = new Uri($this->signUrl($this->prefixer->prefixPath($path), $expiration, $options));

        if (isset($this->config['temporary_url'])) {
            $uri = $this->replaceBaseUrl($uri, $this->config['temporary_url']);
        }

        return (string) $uri;
    }

    /**
     * Get the underlying S3 client.
     */
    public function getClient(): ObsClient
    {
        return $this->obsClient;
    }

    /**
     * Get a signed URL for the file at the given path.
     *
     * @param array<string, mixed> $options
     */
    public function signUrl(
        string $path,
        \DateTimeInterface|int $expiration,
        array $options = [],
        string $method = 'GET'
    ): string {
        $expires = $expiration instanceof \DateTimeInterface ? $expiration->getTimestamp() - time() : $expiration;

        /** @var array{SignedUrl: string} $model */
        $model = $this->obsClient->createSignedUrl([
            'Method' => $method,
            'Bucket' => $this->config['bucket'],
            'Key' => $path,
            'Expires' => $expires,
            'QueryParams' => $options,
        ]);

        return $model['SignedUrl'];
    }
}
