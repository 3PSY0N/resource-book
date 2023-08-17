<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[UniqueEntity(
    fields: ['name'],
    message: 'This name is already in use.'
)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['post:article'])]
    private ?int $id = null;

    #[ORM\Column(length: 25, unique: true)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    #[Gedmo\Slug(fields: ['name'])]
    private ?string $slug = null;
    #[ORM\Column(length: 30, nullable: true)]
    private ?string $bgcolor = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $fgcolor = null;

    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'tags')]
    private Collection $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getBgColor(): ?string
    {
        return $this->bgcolor;
    }

    public function setBgColor(?string $bgcolor): self
    {
        $this->bgcolor = $bgcolor;

        return $this;
    }

    public function getFgColor(): ?string
    {
        return $this->fgcolor;
    }

    public function setFgColor(?string $fgcolor): self
    {
        $this->fgcolor = $fgcolor;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->addTag($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            $article->removeTag($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
