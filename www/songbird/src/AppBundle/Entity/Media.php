<?php

namespace AppBundle\Entity;

use Sonata\MediaBundle\Entity\BaseMedia as BaseMedia;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @author <yourname> <youremail>
 */
class Media extends BaseMedia
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    } 
}