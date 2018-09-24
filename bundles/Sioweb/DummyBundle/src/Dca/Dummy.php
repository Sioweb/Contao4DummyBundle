<?php

/**
 * Contao Open Source CMS
 */

declare (strict_types = 1);
namespace Sioweb\Dummy\Dca;

use Contao\DataContainer;
use Contao\Exception;
use Contao\Image;
use Contao\Input;
use Contao\StringUtil;
use Contao\System;
use Contao\Backend;
use Contao\Versions;
use Contao\CoreBundle\Framework\ContaoFramework;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Dummy
{

    /**
     * @var ContaoFramework
     */
    private $framework;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var SessionInterface
     */
    private $session;

    private $entityManager;

    private $User;

    private $Database = null;

    // Dependency Injection: Die Klasse werden in der Datei /src/Resources/config/services.yml definiert
    public function __construct(ContaoFramework $framework, TokenStorageInterface $tokenStorage, SessionInterface $session, $entityManager)
    {
        $this->framework = $framework;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
        $this->entityManager = $entityManager;

        $token = $this->tokenStorage->getToken();
        $this->User = $token->getUser();
        $this->Database = System::importStatic('Database');
    }

    public function editItem($row, $href, $label, $title, $icon, $attributes)
    {
        return '<a href="' . Backend::addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a> ';
    }

    /**
     * Auto-generate an article alias if it has not been set yet
     * @param mixed
     * @param DataContainer
     * @return string
     * @throws Exception
     */
    public function generateAlias($varValue, DataContainer $dc)
    {
        $autoAlias = false;

        // Generate an alias if there is none
        if ($varValue == '') {
            $autoAlias = true;
            $varValue = standardize(StringUtil::restoreBasicEntities($dc->activeRecord->title));
        }

        $objAlias = $this->Database->prepare("SELECT id FROM tl_dummy WHERE (id=? OR alias=?) AND pid = ?")
            ->execute($dc->id, $varValue, $dc->activeRecord->pid);

        // Check whether the page alias exists
        if ($objAlias->numRows > 1) {
            if (!$autoAlias) {
                throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
            }

            $varValue .= '-' . $dc->id;
        }

        return $varValue;
    }

    /**
     * Return the "toggle visibility" button
     *
     * @param array  $row
     * @param string $href
     * @param string $label
     * @param string $title
     * @param string $icon
     * @param string $attributes
     *
     * @return string
     */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (Input::get('tid') !== null && \strlen(Input::get('tid'))) {
            $this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
            $this->redirect($this->getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$this->User->hasAccess('tl_dummy::published', 'alexf')) {
            return '';
        }

        $href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);

        if (!$row['published']) {
            $icon = 'invisible.gif';
        }

        return '<a href="' . Backend::addToUrl($href) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label, 'data-state="' . ($row['published'] ? 1 : 0) . '"') . '</a> ';
    }

    /**
     * Disable/enable a user group
     *
     * @param integer       $intId
     * @param boolean       $blnVisible
     * @param DataContainer $dc
     */
    public function toggleVisibility($intId, $blnVisible, DataContainer $dc = null)
    {
        // Set the ID and action
        Input::setGet('id', $intId);
        Input::setGet('act', 'toggle');

        if ($dc) {
            $dc->id = $intId;
        }

        // Trigger the onload_callback
        if (\is_array($GLOBALS['TL_DCA']['tl_dummy']['config']['onload_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_dummy']['config']['onload_callback'] as $callback) {
                if (\is_array($callback)) {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                } elseif (\is_callable($callback)) {
                    $callback($dc);
                }
            }
        }

        // Check the field access
        if (!$this->User->hasAccess('tl_dummy::published', 'alexf')) {
            throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to publish/unpublish DUMMY item ID ' . $intId . '.');
        }

        // Set the current record
        if ($dc) {
            $objRow = $this->Database->prepare("SELECT * FROM tl_dummy WHERE id=?")
                ->limit(1)
                ->execute($intId);

            if ($objRow->numRows) {
                $dc->activeRecord = $objRow;
            }
        }

        $objVersions = new Versions('tl_dummy', $intId);
        $objVersions->initialize();

        // Trigger the save_callback
        if (\is_array($GLOBALS['TL_DCA']['tl_dummy']['fields']['published']['save_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_dummy']['fields']['published']['save_callback'] as $callback) {
                if (\is_array($callback)) {
                    $this->import($callback[0]);
                    $blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, $dc);
                } elseif (\is_callable($callback)) {
                    $blnVisible = $callback($blnVisible, $dc);
                }
            }
        }

        $time = time();

        // Update the database
        $this->Database->prepare("UPDATE tl_dummy SET tstamp=$time, published='" . ($blnVisible ? '1' : '') . "' WHERE id=?")
            ->execute($intId);

        if ($dc) {
            $dc->activeRecord->tstamp = $time;
            $dc->activeRecord->published = ($blnVisible ? '1' : '');
        }

        // Trigger the onsubmit_callback
        if (\is_array($GLOBALS['TL_DCA']['tl_dummy']['config']['onsubmit_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_dummy']['config']['onsubmit_callback'] as $callback) {
                if (\is_array($callback)) {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                } elseif (\is_callable($callback)) {
                    $callback($dc);
                }
            }
        }

        $objVersions->create();
    }

    /**
     * Add the source options depending on the allowed fields (see #5498)
     *
     * @param DataContainer $dc
     *
     * @return array
     */
    public function getSourceOptions(DataContainer $dc)
    {
        return array('default_source', 'page', 'internal', 'article', 'external');
    }

    /**
     * Get all articles and return them as array
     *
     * @param DataContainer $dc
     *
     * @return array
     */
    public function getArticleAlias(DataContainer $dc)
    {
        $arrPids = array();
        $arrAlias = array();

        if (!$this->User->isAdmin) {
            foreach ($this->User->pagemounts as $id) {
                $arrPids[] = $id;
                $arrPids = array_merge($arrPids, $this->Database->getChildRecords($id, 'tl_page'));
            }

            if (empty($arrPids)) {
                return $arrAlias;
            }

            $objAlias = $this->Database->prepare("SELECT a.id, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid WHERE a.pid IN(" . implode(',', array_map('intval', array_unique($arrPids))) . ") ORDER BY parent, a.sorting")
                ->execute($dc->id);
        } else {
            $objAlias = $this->Database->prepare("SELECT a.id, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid ORDER BY parent, a.sorting")
                ->execute($dc->id);
        }

        if ($objAlias->numRows) {
            System::loadLanguageFile('tl_article');

            while ($objAlias->next()) {
                $arrAlias[$objAlias->parent][$objAlias->id] = $objAlias->title . ' (' . ($GLOBALS['TL_LANG']['COLS'][$objAlias->inColumn] ?: $objAlias->inColumn) . ', ID ' . $objAlias->id . ')';
            }
        }

        return $arrAlias;
    }
}
