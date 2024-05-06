<?php

namespace Styde\Enlighten\HttpExamples;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class RequestInspector
{
    public function getDataFrom(Request $request)
    {
        return new RequestInfo(
            $request->method(),
            $request->path(),
            $request->headers->all(),
            $request->query(),
            $request->post(),
            $this->getFilesInfo($request->allFiles()),
        );
    }

    public function getFilesInfo(array $files): array
    {
        return collect($files)
            ->map(fn(UploadedFile $file) => [
                'name' => $file->getClientOriginalName(),
                'type' => $file->getMimeType(),
                'size' => intdiv($file->getSize(), 1024),
            ])
            ->all();
    }
}
