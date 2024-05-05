<?php

declare(strict_types=1);

namespace App\Dto\Recipe;

use Symfony\Component\Validator\Constraints\NotBlank;

final readonly class File
{
    public function __construct(
        #[NotBlank]
        public string $path,
        public string $content,
        public bool $executable,
    ) {
    }
}
