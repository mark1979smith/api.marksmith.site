<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 02/10/2017
 * Time: 12:18
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;

/**
 * Article
 *
 * @ORM\Table(name="article_history")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleHistoryRepository")
 * @ORM\Entity @HasLifecycleCallbacks
 */
class ArticleHistory
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
     * @var int
     *
     * @ORM\Column(name="article_id", type="smallint",  options={"unsigned"=true})
     */
    private $articleId;

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
     * @var string
     *
     * @ORM\Column(name="created", type="datetime", options={"default": 0})
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
    public function setId(int $id): ArticleHistory
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
    public function setArticleName(string $articleName): ArticleHistory
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
    public function setArticleSlug(string $articleSlug): ArticleHistory
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
    public function setArticleBody(string $articleBody): ArticleHistory
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
     * @param \DateTime $articleCreated
     *
     * @return Article
     */
    public function setArticleCreated(\DateTime $articleCreated): ArticleHistory
    {
        $this->articleCreated = $articleCreated->format('Y-m-d H:i:s');

        return $this;
    }

    /**
     * @return int
     */
    public function getArticleId(): int
    {
        return $this->articleId;
    }

    /**
     * @param int $articleId
     *
     * @return ArticleHistory
     */
    public function setArticleId(int $articleId): ArticleHistory
    {
        $this->articleId = $articleId;

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

    /** @PrePersist */
    public function onPrePersistSetCreatedDate()
    {
        $articleCreated = new \DateTime();
        $this->articleCreated = $articleCreated;
    }

}
