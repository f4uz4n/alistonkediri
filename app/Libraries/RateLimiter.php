<?php

namespace App\Libraries;

use CodeIgniter\Cache\CacheInterface;
use CodeIgniter\Config\Services;

class RateLimiter
{
    private $cache;
    private $maxAttempts = 5;      // Maksimal percobaan
    private $lockoutTime = 900;    // 15 menit dalam detik

    public function __construct()
    {
        $this->cache = Services::cache();
    }

    /**
     * Cek apakah IP atau username masih bisa melakukan percobaan login
     */
    public function isAllowed(string $identifier): bool
    {
        $key = 'login_attempts_' . md5($identifier);
        $attempts = $this->cache->get($key);

        if ($attempts === null) {
            return true;
        }

        return $attempts < $this->maxAttempts;
    }

    /**
     * Record percobaan login yang gagal
     */
    public function recordFailedAttempt(string $identifier): void
    {
        $key = 'login_attempts_' . md5($identifier);
        $attempts = $this->cache->get($key) ?? 0;
        $attempts++;

        $this->cache->save($key, $attempts, $this->lockoutTime);
    }

    /**
     * Reset percobaan login setelah berhasil
     */
    public function resetAttempts(string $identifier): void
    {
        $key = 'login_attempts_' . md5($identifier);
        $this->cache->delete($key);
    }

    /**
     * Get sisa waktu lockout
     */
    public function getRemainingTime(string $identifier): int
    {
        $key = 'login_attempts_' . md5($identifier);
        // Untuk mendapatkan TTL, kita perlu cek cache info
        // Fallback: return lockout time jika masih ada attempts
        $attempts = $this->cache->get($key);
        if ($attempts !== null && $attempts >= $this->maxAttempts) {
            return $this->lockoutTime;
        }
        return 0;
    }

    /**
     * Get jumlah percobaan yang tersisa
     */
    public function getRemainingAttempts(string $identifier): int
    {
        $key = 'login_attempts_' . md5($identifier);
        $attempts = $this->cache->get($key) ?? 0;
        return max(0, $this->maxAttempts - $attempts);
    }
}
