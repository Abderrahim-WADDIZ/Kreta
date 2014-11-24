<?php

/**
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace Kreta\Component\Core\Uploader\Interfaces;

use Kreta\Component\Core\Model\Interfaces\MediaInterface;

/**
 * Interface MediaUploaderInterface.
 *
 * @package Kreta\Component\Core\Uploader\Interfaces
 */
interface MediaUploaderInterface
{
    /**
     * Uploads the media.
     *
     * @param \Kreta\Component\Core\Model\Interfaces\MediaInterface $media The media
     *
     * @return void
     */
    public function upload(MediaInterface $media);

    /**
     * Removes the media.
     *
     * @param string $name The name
     *
     * @return boolean
     */
    public function remove($name);
}
