<?php

namespace Tests\Integration;

use Illuminate\Http\UploadedFile;
use Styde\Enlighten\Models\ExampleRequest;

class UploadFileTest extends TestCase
{
    /** @test */
    function documents_file_parameters()
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
