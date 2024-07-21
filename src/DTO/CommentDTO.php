<?php

declare(strict_types=1);

namespace App\DTO;

class CommentDTO implements \JsonSerializable
{
    public function __construct(
        public readonly int|null $id,
        public readonly string $name,
        public readonly string $text,
    ) {
    }

    public static function fromArray(array $array): CommentDTO
    {
        return new self($array['id'] ?? null, $array['name'], $array['text']);
    }

    public function equals(CommentDTO $commentDTO): bool
    {
        return $commentDTO->id === $this->id
            && $commentDTO->name === $this->name
            && $commentDTO->text === $this->text;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'text' => $this->text,
        ];
    }
}