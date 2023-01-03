<?php

namespace App\Security\Voter;

use App\Entity\Article;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class ArticleVoter extends Voter
{
    public const VIEW = 'POST_VIEW';

    public function __construct(public RequestStack $requestStack, public Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::VIEW])
            && $subject instanceof \App\Entity\Article;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var Article $article */
        $article = $subject;

        return $this->canView($article);
    }

    private function canView($article): bool
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        if ($article->isPrivateAccess() && null !== $article->getPrivateAccessToken()){
            return $article->getPrivateAccessToken() === $request->query->get('token');
        }

        return false;
    }
}
