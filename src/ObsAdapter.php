<?php

declare(strict_types=1);

namespace Zing\LaravelFlysystem\Obs;

use GuzzleHttp\Psr7\Uri;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\FilesystemOperator;
use Obs\ObsClient;
use Zing\Flysystem\Obs\ObsAdapter as LeagueObsAdapter;

class ObsAdapter extends FilesystemAdapter
{
    /**
     * The OBS client.
     *
     * @var \Obs\ObsClient
     */
    protected $client;

    /**
     * Create a new AwsS3V3FilesystemAdapter instance.
     *
     * @param \League\Flysystem\FilesystemOperator $driver
     * @param \Zing\Flysystem\Obs\ObsAdapter $adapter
     * @param array $config
     * @param \Obs\ObsClient $client
     */
    public function __construct(
        FilesystemOperator $driver,
        LeagueObsAdapter $adapter,
        array $config,
        ObsClient $client
    ) {
        parent::__construct($driver, $adapter, $config);

        $this->client = $client;
    }

    /**
     * Get the URL for the file at the given path.
     *
     * @param string $path
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public function url($path)
    {
        if (isset($this->config['url'])) {
            return $this->concatPathToUrl($this->config['url'], $this->prefixer->prefixPath($path));
        }

        return $this->concatPathToUrl($this->normalizeHost(), $this->prefixer->prefixPath($path));
    }

    protected function normalizeHost(): string
    {
        $endpoint = $this->config['endpoint'];
        if (strpos($endpoint, 'http') !== 0) {
            $endpoint = 'https://' . $endpoint;
        }
        $url = parse_url($endpoint);
        $domain = $url['host'];
        if (! ($this->config['bucket_endpoint'] ?? false)) {
            $domain = $this->config['bucket'] . '.' . $domain;
        }

        $domain = "{$url['scheme']}://{$domain}";

        return rtrim($domain, '/') . '/';
    }

    /**
     * Get a temporary URL for the file at the given path.
     *
     * @param string $path
     * @param \DateTimeInterface $expiration
     * @param array $options
     *
     * @return string
     */
    public function temporaryUrl($path, $expiration, array $options = [])
    {
        $expires = $expiration instanceof \DateTimeInterface ? $expiration->getTimestamp() - time() : $expiration;

        $model = $this->client->createSignedUrl(array_merge([
            'Method' => 'GET',
            'Bucket' => $this->config['bucket'],
            'Key' => $this->prefixer->prefixPath($path),
            'Expires' => $expires,
        ], $options));

        $uri = new Uri($model['SignedUrl']);

        if (isset($this->config['temporary_url'])) {
            $uri = $this->replaceBaseUrl($uri, $this->config['temporary_url']);
        }

        return (string) $uri;
    }
}
