<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\AttachFile\EventSubscriber;

use CarlosChininin\AttachFile\Model\AttachFile;
use CarlosChininin\AttachFile\Service\AttachFileService;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class AttachFileEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly AttachFileService $AttachFileService)
    {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preRemove,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->removeFile($entity);
    }

    private function uploadFile($entity): void
    {
        if (!$entity instanceof AttachFile) {
            return;
        }

        $file = $entity->file();

        if ($file instanceof UploadedFile) {
            $previousPath = $entity->filePath();
            $secure = $this->AttachFileService->upload($file, $entity->folder());
            $entity->setSecure($secure);
            $this->AttachFileService->remove($previousPath);
        }
    }

    private function removeFile($entity): void
    {
        if (!$entity instanceof AttachFile) {
            return;
        }

        $filepath = $entity->filePath();
        $this->AttachFileService->remove($filepath);
    }
}
