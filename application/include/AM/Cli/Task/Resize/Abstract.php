<?php
/**
 * LICENSE
 *
 * This software is governed by the CeCILL-C  license under French law and
 * abiding by the rules of distribution of free software.  You can  use,
 * modify and/ or redistribute the software under the terms of the CeCILL-C
 * license as circulated by CEA, CNRS and INRIA at the following URL
 * "http://www.cecill.info".
 *
 * As a counterpart to the access to the source code and  rights to copy,
 * modify and redistribute granted by the license, users are provided only
 * with a limited warranty  and the software's author,  the holder of the
 * economic rights,  and the successive licensors  have only  limited
 * liability.
 *
 * In this respect, the user's attention is drawn to the risks associated
 * with loading,  using,  modifying and/or developing or reproducing the
 * software by the user in light of its specific status of free software,
 * that may mean  that it is complicated to manipulate,  and  that  also
 * therefore means  that it is reserved for developers  and  experienced
 * professionals having in-depth computer knowledge. Users are therefore
 * encouraged to load and test the software's suitability as regards their
 * requirements in conditions enabling the security of their systems and/or
 * data to be ensured and,  more generally, to use and operate it in the
 * same conditions as regards security.
 *
 * The fact that you are presently reading this means that you have had
 * knowledge of the CeCILL-C license and that you accept its terms.
 *
 * @author Copyright (c) PadCMS (http://www.padcms.net)
 * @version $DOXY_VERSION
 */

/**
 * Abstarct class for resizing tasks
 * @ingroup AM_Cli
 */
abstract class AM_Cli_Task_Resize_Abstract extends AM_Cli_Task_Abstract
{
    /** @var AM_Handler_Thumbnail */
    protected $_oThumbnailer = null; /**< @type AM_Handler_Thumbnail */
    /** @var string */
    protected $_sPreset = null; /**< @type string */

    /**
     * Resizes given image
     * @param string $sFileBaseName
     * @param object $oResource The instance of element | term | horisontal page
     * @param string $sResourceType The type of resource's parent (element, toc, cache-static-pdf)
     * @param string $sResourceKeyName The name of the resource type (resource, thumbnail, etc)
     * @param bool $bZoom
     * @return @void
     */
    protected function _resizeImage($sFileBaseName, $oResource, $sResourceType, $sResourceKeyName, $sResourcePresetName = null, $bZoom = false)
    {
        if (is_null($sResourcePresetName)) {
            $sResourcePresetName = $sResourceType;
        }

        $sImageType = $oResource->getResources()->getImageType();

        $sFileExtension = strtolower(pathinfo($sFileBaseName, PATHINFO_EXTENSION));

        $sFilePath = AM_Tools::getContentPath($sResourceType, $oResource->id)
                    . DIRECTORY_SEPARATOR
                    . $sResourceKeyName . '.' . $sFileExtension;

        $this->_oThumbnailer->clearSources()
                ->addSourceFile($sFilePath)
                ->setImageType($sImageType)
                ->setZooming($bZoom);

        if (empty($this->_sPreset)) {
            $this->_oThumbnailer->loadAllPresets($sResourcePresetName);
        } else {
            $this->_oThumbnailer->clearPresets()->addPreset($this->_sPreset);
        }

        $this->_oThumbnailer->createThumbnails();

        $this->_echo(sprintf('%s', $sFilePath), 'success');
    }
}