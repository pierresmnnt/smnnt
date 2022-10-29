<?php

namespace App\EventListener;

use App\Event\PublishedArticleEvent;
use DateTimeImmutable;

class ArticleListener
{
    public function onPublishedArticleListener(PublishedArticleEvent $event) {
        $event->getArticle()->setPublishedAt(new DateTimeImmutable());
    }
}