<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DatabaseConnectionTest extends TestCase
{
    /**
     * Test MySQL Connection.
     *
     * @return void
     */
    public function test_mysql_connection()
    {
        // Cek apakah koneksi ke MySQL berhasil
        try {
            $result = DB::connection('mysql')->select('SELECT 1');
            $this->assertNotEmpty($result);
            $this->info('MySQL connection is successful.');
        } catch (\Exception $e) {
            $this->fail('MySQL connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Test PostgreSQL Connection.
     *
     * @return void
     */
    public function test_pgsql_connection()
    {
        // Cek apakah koneksi ke PostgreSQL berhasil
        try {
            $result = DB::connection('pgsql')->select('SELECT 1');
            $this->assertNotEmpty($result);
            $this->info('PostgreSQL connection is successful.');
        } catch (\Exception $e) {
            $this->fail('PostgreSQL connection failed: ' . $e->getMessage());
        }
    }
}
