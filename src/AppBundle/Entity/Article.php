<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleRepository")
 * @ORM\Entity @HasLifecycleCallbacks
 */
class Article
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="smallint", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $articleName;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=40)
     */
    private $articleSlug;

    /**
     * @var string
     *
     * @ORM\Column(name="article_teaser", type="string", length=255)
     */
    private $articleTeaser;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $articleBody;

    /**
     * @var string|null
     *
     * @ORM\Column(name="created", type="datetime", options={"default": 0})
     */
    private $articleCreated;

    /**
     * @var string|null
     *
     * @ORM\Column(name="updated", type="datetime", options={"default": 0})
     */
    private $articleUpdated;

    /**
     * @return null|int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Article
     */
    public function setId(int $id): Article
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getArticleName(): string
    {
        return $this->articleName;
    }

    /**
     * @param string $articleName
     *
     * @return Article
     */
    public function setArticleName(string $articleName): Article
    {
        $this->articleName = $articleName;

        return $this;
    }

    /**
     * @return string
     */
    public function getArticleSlug(): string
    {
        return $this->articleSlug;
    }

    /**
     * @param string $articleSlug
     *
     * @return Article
     */
    public function setArticleSlug(string $articleSlug): Article
    {
        $this->articleSlug = $articleSlug;

        return $this;
    }

    /**
     * @return string
     */
    public function getArticleBody(): string
    {
        return $this->articleBody;
    }

    /**
     * @param string $articleBody
     *
     * @return Article
     */
    public function setArticleBody(string $articleBody): Article
    {
        $this->articleBody = $articleBody;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getArticleCreated(): ?string
    {
        return $this->articleCreated;
    }

    /**
     * @param \DateTime $articleCreated
     *
     * @return Article
     */
    public function setArticleCreated(\DateTime $articleCreated): Article
    {
        $this->articleCreated = $articleCreated->format('Y-m-d H:i:s');

        return $this;
    }

    /**
     * @return string
     */
    public function getArticleTeaser(): string
    {
        return $this->articleTeaser;
    }

    /**
     * @param string $articleTeaser
     *
     * @return Article
     */
    public function setArticleTeaser(string $articleTeaser): Article
    {
        $this->articleTeaser = $articleTeaser;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getArticleUpdated(): ?string
    {
        return $this->articleUpdated;
    }

    /**
     * @param string $articleUpdated
     *
     * @return Article
     */
    public function setArticleUpdated(string $articleUpdated): Article
    {
        $this->articleUpdated = $articleUpdated;

        return $this;
    }

    /** @PrePersist */
    public function onPrePersistSetCreatedDate()
    {
        $articleCreated = new \DateTime();
        $this->articleCreated = $articleCreated;
        $this->articleUpdated = $articleCreated;
    }

    /** @PreUpdate */
    public function onPreUpdateSetUpdatedDate()
    {
        $articleUpdated = new \DateTime();
        $this->articleUpdated = $articleUpdated;
    }

}

