<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Doctrine\ORM\EntityManagerInterface;

class PlainPasswordAuthenticationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UrlGeneratorInterface $urlGenerator
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            CheckPassportEvent::class => ['onCheckPassport', 512],
        ];
    }

    public function onCheckPassport(CheckPassportEvent $event): void
    {
        $passport = $event->getPassport();

        if (!$passport instanceof Passport) {
            return;
        }

        $userBadge = $passport->getBadge(UserBadge::class);
        if (!$userBadge instanceof UserBadge) {
            return;
        }

        $user = $userBadge->getUser();
        if (!$user instanceof User) {
            return;
        }

        $credentials = $passport->getBadge(PasswordCredentials::class);
        if (!$credentials instanceof PasswordCredentials) {
            return;
        }

        $plainPassword = $credentials->getPassword();
        $hashedPassword = $user->getPassword();

        if ($plainPassword === $hashedPassword) {
            $credentials->markResolved();
        }
    }
}
