<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Google\Client;
use Google\Service\Drive;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;

class GoogleDriveServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            Storage::extend('google', function ($app, $config) {
                $options = [];
                
                if (!empty($config['teamDriveId'] ?? null)) {
                    $options['teamDriveId'] = $config['teamDriveId'];
                }

                $client = new Client();
                
                // Check if service account file exists
                if (isset($config['serviceAccountFile']) && file_exists($config['serviceAccountFile'])) {
                    $client->setAuthConfig($config['serviceAccountFile']);
                    $client->addScope(Drive::DRIVE_FILE);
                    $client->setSubject(null); // Service account doesn't need subject
                } else {
                    // Fallback to client credentials
                    $client->setClientId($config['clientId']);
                    $client->setClientSecret($config['clientSecret']);
                    $client->refreshToken($config['refreshToken']);
                }

                $service = new Drive($client);
                $adapter = new GoogleDriveAdapter($service, $config['folder'] ?? '/', $options);
                
                return new Filesystem($adapter, ['case_sensitive' => false]);
            });
        } catch (\Exception $e) {
            \Log::error('Google Drive Service Provider Error: ' . $e->getMessage());
        }
    }
}

