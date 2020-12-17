<?php
namespace App\Subscriber;

use App\Entity\Image;
use App\Service\ImageService;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PostImageSubscriber implements EventSubscriberInterface
{
    private $imagesService;

    public function __construct(ImageService $imagesService)
    {
        $this->imagesService = $imagesService;
    }

    public static function getSubscribedEvents()
    {
        return array(
            BeforeEntityPersistedEvent::class => array('postImage'),
        );
    }

    function postImage(BeforeEntityPersistedEvent $event) {
        $result = $event->getEntityInstance();

        if (!($result instanceof Image)) {
            return;
        }
        $imageName = $this->imagesService->saveToDisk($result->getImage());
        $result->setFilename($imageName)->setFilePath("/uploads/images/" . $imageName);

    }
}