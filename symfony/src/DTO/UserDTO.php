<?php

namespace App\DTO;

use App\Entity\User;

final readonly class UserDTO
{
    public function __construct(
        public int $id,
        public string $nom,
        public string $prenom,
        public string $email,
        public ?string $adresse,
        public ?string $telephone,
        public string $role,
        public bool $isArchive,
        public \DateTimeInterface $createdAt,
        public \DateTimeInterface $updatedAt,
    ) {}

    public static function fromEntity(User $user): self
    {
        return new self(
            id: $user->getId(),
            nom: $user->getNom(),
            prenom: $user->getPrenom(),
            email: $user->getEmail(),
            adresse: $user->getAdresse(),
            telephone: $user->getTelephone(),
            role: $user->getRole(),
            isArchive: $user->isArchive(),
            createdAt: $user->getCreatedAt(),
            updatedAt: $user->getUpdatedAt(),
        );
    }

    /**
     * @param User[] $users
     * @return self[]
     */
    public static function fromEntities(array $users): array
    {
        return array_map(fn(User $user) => self::fromEntity($user), $users);
    }

    public function getFullName(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }
}
