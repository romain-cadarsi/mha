<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BlogRepository::class)
 */
class Blog
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
    private $title;

    /**
     * @ORM\Column(type="date")
     */
    private $publishDate;

    /**
     * @ORM\Column(type="string", length=999999999, nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $metadescription;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titleH1;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $slug = new Slugify();
        $this->slug = $slug->slugify($title);
        $this->title = $title;

        return $this;
    }

    public function getPublishDate(): ?\DateTimeInterface
    {
        return $this->publishDate;
    }

    public function setPublishDate(\DateTimeInterface $publishDate): self
    {
        $this->publishDate = $publishDate;

        return $this;
    }

    public function getContent(): ?string
    {
     return $this->content;
    }

    public function setContent(?string $content): self
    {
       $this->content = $content;

        return $this;
    }

    public function getDisplayContent(){
        $pattern = '/h1/i';
        $replacement = 'h2';
        return preg_replace($pattern, $replacement, $this->content);
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getMetadescription(): ?string
    {
        return $this->metadescription;
    }

    public function setMetadescription(string $metadescription): self
    {
        $this->metadescription = $metadescription;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }


    public function __toString(){
        return $this->title;
    }

    public function getTitleH1(): ?string
    {
        return $this->titleH1;
    }

    public function setTitleH1(string $titleH1): self
    {
        $this->titleH1 = $titleH1;

        return $this;
    }

}
