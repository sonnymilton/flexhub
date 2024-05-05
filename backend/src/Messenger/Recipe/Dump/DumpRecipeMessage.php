<?php

declare(strict_types=1);

namespace App\Messenger\Recipe\Dump;

use Symfony\Component\Uid\Uuid;

final readonly class DumpRecipeMessage
{
    public function __construct(public Uuid $recipeId)
    {
    }
}
