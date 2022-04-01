<?php

namespace App;

use App\Entity\Comment;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpamChecker
{
    private $client;
    private $endpoint;

    public function __construct(HttpClientInterface $client, string $akismentKey)
    {
        $this->client = $client;
        $this->endpoint = sprintf('http://oleg.korzill.ru/spamchecker/?key=%s', $akismentKey);
    }

    public function getSpamScore(Comment $comment, array $context): int
    {
        $response = $this->client->request('POST', $this->endpoint, [
            'body' => array_merge($context, [
                'blog' => 'http://127.0.0.1:8000',
                'comment_type' => 'comment',
                'comment_author' => $comment->getAuthor(),
                'comment_author_email' => $comment->getEmail(),
                'comment_content' => $comment->getText(),
                'comment_date_gmt' => $comment->getCreatedAt()->format('c'),
                'blog_lang' => 'en',
                'blog_charset' => 'UTF-8',
                'is_test' => true,
            ]),
        ]);

        $headers = $response->getHeaders();

        if ('discard' === ($headers['spamvalue'][0] ?? '')) {
            return 2;
        }
        
        $content = $response->getContent();
        if (isset($headers['spamvalue'][0])) {
            throw new \RuntimeException(sprintf('Unable to check for spam: %s (%s).', $content, $headers['spamvalue'][0]));
        }

        return 'true' === $content ? 1 : 0;
    }
}
