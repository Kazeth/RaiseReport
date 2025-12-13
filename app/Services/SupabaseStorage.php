<?php

namespace App\Services;

use GuzzleHttp\Client;

class SupabaseStorage
{
    protected Client $client;
    protected string $url;
    protected string $serviceKey;
    protected string $bucket;

    public function __construct()
    {
        $this->url        = rtrim(config('services.supabase.url'), '/');
        $this->serviceKey = config('services.supabase.service_key');
        $this->bucket     = config('services.supabase.bucket');

        $this->client = new Client([
            'base_uri' => $this->url,
            'timeout'  => 30,
        ]);
    }

    public function upload($file, string $path): string
    {
        $this->client->post(
            "/storage/v1/object/{$this->bucket}/{$path}",
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->serviceKey,
                    'Content-Type'  => $file->getMimeType(),
                ],
                'body' => fopen($file->getRealPath(), 'r'),
            ]
        );

        return "{$this->url}/storage/v1/object/public/{$this->bucket}/{$path}";
    }

    public function uploadFromPath(string $localPath, string $remotePath): string
    {
        $this->client->post(
            "/storage/v1/object/{$this->bucket}/{$remotePath}",
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->serviceKey,
                    'Content-Type'  => mime_content_type($localPath),
                ],
                'body' => fopen($localPath, 'r'),
            ]
        );

        return "{$this->url}/storage/v1/object/public/{$this->bucket}/{$remotePath}";
    }

    public function delete(string $publicUrl): bool
    {
        $parsed = parse_url($publicUrl);
        $path   = $parsed['path'] ?? '';

        $prefix = "/storage/v1/object/public/{$this->bucket}/";
        $objectPath = str_replace($prefix, '', $path);

        $response = $this->client->delete(
            "/storage/v1/object/{$this->bucket}/{$objectPath}",
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->serviceKey,
                ],
            ]
        );

        return $response->getStatusCode() === 200;
    }
}
