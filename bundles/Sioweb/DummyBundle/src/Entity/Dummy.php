<?php

namespace Sioweb\Dummy\Entity;
use \Doctrine\ORM\Mapping as ORM;

/**
 * Class Log
 *
 * @ORM\Entity
 * @ORM\Table(name="tl_dummy")
 * @ORM\Entity(repositoryClass="Sioweb\Dummy\Repository\DummyRepository")
 */
class Terms
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    protected $tstamp;

    /**
     * @var string
     * @ORM\Column(type="string", options={"default" : ""})
     */
    protected $title;

    /**
     * @var string
     * @ORM\Column(type="string", options={"default" : ""})
     */
    protected $alias;

    /**
     * @var string
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    protected $description;

    /**
     * @var string
     * @ORM\Column(type="string", length=1, options={"default" : ""})
     */
    protected $published = 1;

    /**
     * @var string
     * @ORM\Column(type="string", options={"default" : ""})
     */
    protected $start;

    /**
     * @var string
     * @ORM\Column(type="string", options={"default" : ""})
     */
    protected $stop;


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the value of tstamp
     *
     * @return  int
     */ 
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * Set the value of tstamp
     *
     * @param  int  $tstamp
     *
     * @return  self
     */ 
    public function setTstamp(int $tstamp)
    {
        $this->tstamp = $tstamp;

        return $this;
    }

    /**
     * Get the value of title
     *
     * @return  string
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param  string  $title
     *
     * @return  self
     */ 
    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of alias
     *
     * @return  string
     */ 
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set the value of alias
     *
     * @param  string  $alias
     *
     * @return  self
     */ 
    public function setAlias(string $alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get the value of description
     *
     * @return  string
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param  string  $description
     *
     * @return  self
     */ 
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of published
     *
     * @return  string
     */ 
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set the value of published
     *
     * @param  string  $published
     *
     * @return  self
     */ 
    public function setPublished(string $published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get the value of start
     *
     * @return  string
     */ 
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set the value of start
     *
     * @param  string  $start
     *
     * @return  self
     */ 
    public function setStart(string $start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get the value of stop
     *
     * @return  string
     */ 
    public function getStop()
    {
        return $this->stop;
    }

    /**
     * Set the value of stop
     *
     * @param  string  $stop
     *
     * @return  self
     */ 
    public function setStop(string $stop)
    {
        $this->stop = $stop;

        return $this;
    }
}