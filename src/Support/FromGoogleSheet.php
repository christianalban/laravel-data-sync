<?php

namespace Alban\LaravelDataSync\Support;

interface FromGoogleSheet {
    public function getSheetId(): string;

    public function getSheetName(): string|array;
}
