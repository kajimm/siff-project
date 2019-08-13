<?php
namespace Core\Encryp;
/**
 * Core\Encryp\EncryptInterface
 */
interface EncryptInterface
{
    public function encrypt($password): ?string;

    public function decrypt(string $password): ?string;

    public function validator(string $password): bool;

    public function csrfToken(): void;

    public function useToken(): ?array;

}
