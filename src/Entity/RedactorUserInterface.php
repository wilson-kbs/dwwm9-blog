<?php

namespace App\Entity;

interface RedactorUserInterface
{
  public function getUser(): ?User;
  public function setUser(?User $user): self;
}
