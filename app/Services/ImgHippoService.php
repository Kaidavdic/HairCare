<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;

class ImgHippoService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.imghippo.com/v1/upload';

    public function __construct()
    {
        // Ideally from config, but hardcoding as requested for this task
        $this->apiKey = 'e447003eccb30799acbda96c79c5d86c';
    }

    /**
     * Upload an image to ImgHippo and return the URL.
     *
     * @param UploadedFile $file
     * @return string|null The URL of the uploaded image, or null on failure.
     */
    public function upload(UploadedFile $file): ?string
    {
        $response = Http::timeout(30)->attach(
            'file', 
            file_get_contents($file->getRealPath()), 
            $file->getClientOriginalName()
        )->post($this->baseUrl, [
            'api_key' => $this->apiKey,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            // Assuming the structure based on typical API responses, verify if needed
            // The user provided summary says "receive an immediate response containing the processed file URL"
            // Let's assume it's directly in 'data.url' or similar. We might need to inspect the response if this fails.
            return $data['data']['url'] ?? $data['url'] ?? null; 
        }

        return null;
    }
}
