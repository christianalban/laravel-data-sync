<?php

namespace Alban\LaravelDataSync\Support;

interface MustApplyFilter {
    public function filter($item): bool;
}
