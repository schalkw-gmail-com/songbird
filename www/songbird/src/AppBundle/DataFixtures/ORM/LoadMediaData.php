<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Gallery;
use AppBundle\Entity\GalleryHasMedia;
use AppBundle\Entity\Media;


class LoadMediaData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        // create test1 gallery 1
        $gallery1 = new Gallery();
        $gallery1->setName('gallery 1');
        $gallery1->setEnabled(1);
        $gallery1->setDefaultFormat('user_small');
        $gallery1->setContext('default');
        $em->persist($gallery1);

        // create test1 gallery 2
        $gallery2 = new Gallery();
        $gallery2->setName('gallery 2');
        $gallery2->setEnabled(1);
        $gallery2->setDefaultFormat('user_small');
        $gallery2->setContext('default');
        $em->persist($gallery2);

        // create test2 gallery 3
        $gallery3 = new Gallery();
        $gallery3->setName('gallery 3');
        $gallery3->setEnabled(1);
        $gallery3->setDefaultFormat('user_small');
        $gallery3->setContext('default');
        $em->persist($gallery3);

        // create media 1
        $media1 = new Media();
        $media1->setName('file 1');
        $media1->setEnabled(1);
        $media1->setDescription('test file 1.');
        $media1->setProviderName('sonata.media.provider.file');
        $media1->setProviderStatus(1);
        $media1->setProviderReference('testref1.zip');
        $media1->setproviderMetadata(array('filename'=>'something1.zip'));
        $media1->setContext('default');
        $em->persist($media1);

        // create media 2
        $media2 = new Media();
        $media2->setName('file 2');
        $media2->setEnabled(1);
        $media2->setDescription('test file 2.');
        $media2->setProviderName('sonata.media.provider.file');
        $media2->setProviderStatus(1);
        $media2->setProviderReference('testref2.zip');
        $media2->setproviderMetadata(array('filename'=>'something2.zip'));
        $media2->setContext('default');
        $em->persist($media2);

        // create media 3
        $media3 = new Media();
        $media3->setName('file 3');
        $media3->setEnabled(1);
        $media3->setDescription('test file 3.');
        $media3->setProviderName('sonata.media.provider.file');
        $media3->setProviderStatus(1);
        $media3->setProviderReference('testref3.zip');
        $media3->setproviderMetadata(array('filename'=>'something3.zip'));
        $media3->setContext('default');
        $em->persist($media3);

        // add media 1 to gallery 1
        $gallery_has_media1 = new GalleryHasMedia();
        $gallery_has_media1->setGallery($gallery1);
        $gallery_has_media1->setMedia($media1);
        $gallery_has_media1->setEnabled(1);
        $gallery_has_media1->setPosition(1);
        $em->persist($gallery_has_media1);

        // add test media 2 to gallery 2
        $gallery_has_media2 = new GalleryHasMedia();
        $gallery_has_media2->setGallery($gallery2);
        $gallery_has_media2->setMedia($media2);
        $gallery_has_media2->setEnabled(1);
        $gallery_has_media2->setPosition(2);
        $em->persist($gallery_has_media2);

        // add test media 3 to gallery 3
        $gallery_has_media3 = new GalleryHasMedia();
        $gallery_has_media3->setGallery($gallery3);
        $gallery_has_media3->setMedia($media3);
        $gallery_has_media3->setEnabled(1);
        $gallery_has_media3->setPosition(1);
        $em->persist($gallery_has_media3);

        // // save all
        $em->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        // load user data
        return 2;
    }
}