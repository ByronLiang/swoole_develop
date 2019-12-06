<?php

namespace Modules\ClientAggregationUpload;

interface FactoryInterface
{
    public function getForm(): array;

    public function getHeaders(): array;

    public function getAccessUrl(): String;

    public function getUploadUrl(): String;

    public function getFileField(): String;
}
