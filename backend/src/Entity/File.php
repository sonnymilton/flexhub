<?php

declare(strict_types=1);

namespace App\Entity;

use App\Flex\Recipe\Dump\DumpRecipeContext;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(readOnly: true)]
class File
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $path;

    #[ORM\Column(type: 'text')]
    private string $content;

    #[ORM\Column(type: 'boolean')]
    private bool $executable;

    public function __construct(string $path, string $content, bool $executable)
    {
        $this->id = Uuid::v4();

        $this->path = $path;
        $this->content = $content;
        $this->executable = $executable;
    }

    public function dumpInto(DumpRecipeContext $context): void
    {
        $context->addFile($this->path, [
            'contents' => explode("\n", $this->content),
            'executable' => $this->executable,
        ]);
    }
}
