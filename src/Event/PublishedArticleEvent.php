<?php

namespace App\Event;

use App\Entity\Article;
use Symfony\Contracts\EventDispatcher\Event;

class PublishedArticleEvent extends Event
{
    public const NAME = 'article.published';

    public function __construct (private Article $article){}

    public function getArticle(): Article{
        return $this->article;
    }
}
