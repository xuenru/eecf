<?php
namespace Annonce\Model;

class History
{
    /** @var  int */
    protected $id;
    /** @var  int */
    protected $annonceId;
    /** @var  int */
    protected $statusTo;
    /** @var  string */
    protected $statusToLabel;
    /** @var  string */
    protected $comment;
    /** @var  int */
    protected $author;
    /** @var  string */
    protected $authorLastname;
    /** @var  string */
    protected $authorFirstname;
    /** @var  string */
    protected $updateAt;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return History
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getAnnonceId()
    {
        return $this->annonceId;
    }

    /**
     * @param int $annonceId
     *
     * @return History
     */
    public function setAnnonceId($annonceId)
    {
        $this->annonceId = $annonceId;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatusTo()
    {
        return $this->statusTo;
    }

    /**
     * @param int $statusTo
     *
     * @return History
     */
    public function setStatusTo($statusTo)
    {
        $this->statusTo = $statusTo;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatusToLabel()
    {
        return $this->statusToLabel;
    }

    /**
     * @param string $statusToLabel
     *
     * @return History
     */
    public function setStatusToLabel($statusToLabel)
    {
        $this->statusToLabel = $statusToLabel;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     *
     * @return History
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return int
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getAuthorLastname()
    {
        return $this->authorLastname;
    }

    /**
     * @param string $authorLastname
     *
     * @return History
     */
    public function setAuthorLastname($authorLastname)
    {
        $this->authorLastname = $authorLastname;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthorFirstname()
    {
        return $this->authorFirstname;
    }

    /**
     * @param string $authorFirstname
     *
     * @return History
     */
    public function setAuthorFirstname($authorFirstname)
    {
        $this->authorFirstname = $authorFirstname;
        return $this;
    }

    /**
     * @param int $author
     *
     * @return History
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdateAt()
    {
        return date('d/m/Y H:i:s', strtotime($this->updateAt));
    }

    /**
     * @param string $updateAt
     *
     * @return History
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;
        return $this;
    }


}