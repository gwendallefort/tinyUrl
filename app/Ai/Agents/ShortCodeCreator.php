<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

#[Timeout(10)]
class ShortCodeCreator implements Agent, HasStructuredOutput
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
            You suggest short URL aliases (custom path segments) for a link shortener.

            Rules for every suggestion:
            - 3 to 20 characters long
            - Only letters, numbers, hyphens, and underscores (no spaces or other punctuation)
            - Prefer memorable, readable aliases inspired by the title and destination URL
            - Prefer lowercase kebab-case when using multiple words (e.g. docs-guide)
            - Avoid reserved or generic words such as: login, admin, api, dashboard, profile, url, link, short
            - Avoid two-character codes and pure numbers
            - Return exactly 5 unique suggestions
            PROMPT;
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'suggestions' => $schema->array()
                ->items($schema->string()->min(3)->max(20))
                ->min(5)
                ->max(5)
                ->unique()
                ->required(),
        ];
    }
}
