<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LoanApiService
{
    private $baseUrl;
    private $authEmail;
    private $authPassword;
    private $tokenCacheKey = 'wgss_api_token';

    public function __construct()
    {
        // Use environment variables if available, otherwise use defaults
        $this->baseUrl = env('WGSS_API_URL', 'http://172.28.100.44:8000/api');
        $this->authEmail = env('WGSS_API_EMAIL', 'irfan.luthfi@bankwoorisaudara.com');
        $this->authPassword = env('WGSS_API_PASSWORD', 'Bws@1906');
        
        // Log only in debug mode
        if (config('app.debug')) {
            Log::info('LoanApiService initialized', [
                'baseUrl' => $this->baseUrl,
                'email' => $this->authEmail
            ]);
        }
    }

    /**
     * Get authentication token from cache or API
     */
    private function getAuthToken()
    {
        // Check if token exists in cache
        $cachedToken = Cache::get($this->tokenCacheKey);
        if ($cachedToken) {
            return $cachedToken;
        }

        try {
            // Login to get new token - disable SSL verification for self-signed certificates
            $response = Http::withoutVerifying()->post($this->baseUrl . '/auth/login', [
                'email' => $this->authEmail,
                'password' => $this->authPassword,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $token = $data['access_token'];
                $expiresIn = $data['expires_in'] ?? 3600; // Default 1 hour if not provided

                // Cache token for 90% of its lifetime to ensure it doesn't expire
                $cacheTime = ($expiresIn * 0.9) / 60; // Convert to minutes
                Cache::put($this->tokenCacheKey, $token, $cacheTime);

                // Log authentication success only in debug mode
                if (config('app.debug')) {
                    Log::info('Successfully authenticated with WGSS API');
                }
                return $token;
            } else {
                Log::error('Authentication Failed', [
                    'url' => $this->baseUrl . '/auth/login',
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                throw new \Exception('Failed to authenticate with WGSS API');
            }
        } catch (\Exception $e) {
            Log::error('Authentication Error: ' . $e->getMessage(), [
                'url' => $this->baseUrl . '/auth/login'
            ]);
            throw $e;
        }
    }

    /**
     * Get loan data by loan application number
     */
    public function getLoanData($loanAppNo)
    {
        try {
            $token = $this->getAuthToken();

            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . '/v1/dms/loan/' . $loanAppNo);

            if ($response->successful()) {
                $data = $response->json();
                
                // Log response only in debug mode
                if (config('app.debug')) {
                    Log::info('API Response received successfully');
                }
                
                // The API returns an array, we need the first element
                if (is_array($data) && !empty($data)) {
                    return $this->mapLoanData($data[0]);
                } else {
                    Log::warning('API returned empty or invalid data structure', ['data' => $data]);
                    return null;
                }
            } else {
                // If token expired, clear cache and retry once
                if ($response->status() === 401) {
                    Cache::forget($this->tokenCacheKey);
                    return $this->getLoanData($loanAppNo); // Retry once
                }

                Log::error('API Error: HTTP ' . $response->status(), [
                    'url' => $this->baseUrl . '/v1/dms/loan/' . $loanAppNo,
                    'loan_app_no' => $loanAppNo,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('API Error: ' . $e->getMessage(), [
                'url' => $this->baseUrl . '/v1/dms/loan/' . $loanAppNo,
                'loan_app_no' => $loanAppNo
            ]);
            return null;
        }
    }

    /**
     * Map API response to match the expected format
     */
    private function mapLoanData($apiData)
    {
        // Ensure apiData is an array
        if (is_object($apiData)) {
            $apiData = (array) $apiData;
        }

        $mappedData = (object) [
            'loan_app_no' => $apiData['LOAN_APP_NO'] ?? '',
            'no_cif' => $apiData['NO_CIF'] ?? '',
            'debtor_classification_code' => $apiData['DEBTOR_CLASSIICATION_CODE'] ?? '',
            'debtor_classification_name' => $apiData['DEBTOR_CLASSIICATION_NAME'] ?? '',
            'kode_cabang' => $apiData['KODE_CABANG'] ?? '',
            'nama_cabang' => $apiData['NAMA_CABANG'] ?? '',
            'nama_debitur' => $apiData['NAMA_DEBITUR'] ?? '',
            'no_ktp' => $apiData['NO_KTP'] ?? '',
            'tgl_lahir' => $apiData['TGL_LAHIR'] ?? '',
            'alamat_rumah' => $apiData['ALAMAT_RUMAH'] ?? '',
            'instansi' => $apiData['INSTANSI'] ?? '',
            'alamat_kantor' => $apiData['ALAMAT_KANTOR'] ?? '',
            'no_tlp_kantor' => $apiData['NO_TLP_KANTOR'] ?? '',
            'produk' => $apiData['PRODUK'] ?? '',
            'plafond' => $apiData['PLAFOND'] ?? '',
            'jangka_waktu' => $apiData['JANGKA_WAKTU'] ?? '',
            'rate' => $apiData['RATE'] ?? '',
            'angsuran' => $apiData['ANGSURAN'] ?? '',
            'tgl_jatuh_tempo' => $apiData['TGL_JATUH_TEMPO'] ?? '',
            'kd_status_pernikahan' => $apiData['KD_STATUS_PERNIKAHAN'] ?? '',
            'status_pernikahan' => $apiData['STATUS_PERNIKAHAN'] ?? '',
        ];

        // Log mapping only in debug mode
        if (config('app.debug')) {
            Log::info('Data mapped successfully', ['loan_app_no' => $mappedData->loan_app_no]);
        }

        return $mappedData;
    }

    /**
     * Clear cached token (useful for testing or forced refresh)
     */
    public function clearTokenCache()
    {
        Cache::forget($this->tokenCacheKey);
    }
}