<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Main\TenantRepository")
 */
class Tenant
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dbName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dbUser;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dbPassword;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDbName(): ?string
    {
        return $this->dbName;
    }

    public function setDbName(string $dbName): self
    {
        $this->dbName = $dbName;

        return $this;
    }

    public function getDbUser(): ?string
    {
        return $this->dbUser;
    }

    public function setDbUser(string $dbUser): self
    {
        $this->dbUser = $dbUser;

        return $this;
    }

    public function getDbPassword(): ?string
    {
        return $this->dbPassword;
    }

    public function setDbPassword(string $dbPassword): self
    {
        $this->dbPassword = $dbPassword;

        return $this;
    }
}
