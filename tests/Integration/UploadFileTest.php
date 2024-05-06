<?php

namespace Tests\Integration;

use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\Models\ExampleRequest;

class UploadFileTest extends TestCase
{
    #[Test]
    function documents_file_parameters(): void
    {
        $this->withoutExceptionHandling();

        $this->postJson('upload-photo?username=duilio', [
            'photo' => UploadedFile::fake()->create('photo.jpg', 2000),
            'description' => 'Duilio Palacios',
        ])->assertOk();

        tap(ExampleRequest::first(), function ($exampleRequest) {
            $expected = [
                'photo' => [
                    'name' => 'photo.jpg',
                    'type' => 'image/jpeg',
                    'size' => 2000,
                ],
            ];
            $this->assertSame($expected, $exampleRequest->request_files);
        });
    }
}
