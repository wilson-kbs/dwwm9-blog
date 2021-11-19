<?php

namespace App\Entity;

interface UserRedactorInterface
{
  public function getRedactor(): ?Redactor;
  public function setRedactor(?Redactor $redactor): self;
}
