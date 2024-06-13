<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Flex\Index\IndexLoaderInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @phpstan-import-type FlexIndex from IndexLoaderInterface
 */
#[Route('/flex')]
#[OA\Tag('Flex')]
final class FlexController extends AbstractController
{
    /** @return FlexIndex */
    #[Route('/index.json', methods: ['GET'])]
    public function index(Request $request, IndexLoaderInterface $indexLoader, ParameterBagInterface $bag): array
    {
        $host = $bag->get('app.host_url') ?? $request->getSchemeAndHttpHost();

        return $indexLoader->load($host);
    }
}
