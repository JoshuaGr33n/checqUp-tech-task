<?php

namespace Tests\Unit\Application\Users\Services;

use App\Application\Users\Services\FileUploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class FileUploadServiceTest extends TestCase
{
    private $service;

    /**
     * Set up the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->service = new FileUploadService();
    }

    /**
     * Test that files are uploaded correctly.
     *
     * @return void
     */
    #[Test]
    public function it_uploads_files_correctly()
    {
        $file = UploadedFile::fake()->image('avatar.jpg');
        $path = $this->service->upload($file);
        
        Storage::disk('public')->assertExists($path);
    }

    /**
     * Test that files are deleted correctly.
     *
     * @return void
     */
    #[Test]
    public function it_deletes_files_correctly()
    {
        $file = UploadedFile::fake()->image('avatar.jpg');
        $path = $this->service->upload($file);
        Storage::disk('public')->assertExists($path);
        
        $result = $this->service->delete($path);
        $this->assertTrue($result);
        Storage::disk('public')->assertMissing($path);
    }
}
