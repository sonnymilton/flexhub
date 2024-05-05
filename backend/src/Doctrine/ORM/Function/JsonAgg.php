<?php

declare(strict_types=1);

namespace App\Doctrine\ORM\Function;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

final class JsonAgg extends FunctionNode
{
    private Node|string $expr;

    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->expr = $parser->ArithmeticPrimary();
        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        if (!$this->expr instanceof Node) {
            throw new \RuntimeException(sprintf('Expecting expr to be an instance of %s', Node::class));
        }

        return sprintf('JSON_AGG(%s)', $this->expr->dispatch($sqlWalker));
    }
}
