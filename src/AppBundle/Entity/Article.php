<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleRepository")
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
     * @ORM\Column(name="slug", type="string", length=30)
     */
    private $articleSlug;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $articleBody;

    /**
     * @var string
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $articleCreated;

    /**
     * @return int
     */
    public function getId(): int
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
     * @return string
     */
    public function getArticleCreated(): string
    {
        return $this->articleCreated;
    }

    /**
     * @param string $articleCreated
     *
     * @return Article
     */
    public function setArticleCreated(string $articleCreated): Article
    {
        $this->articleCreated = $articleCreated;

        return $this;
    }

}

