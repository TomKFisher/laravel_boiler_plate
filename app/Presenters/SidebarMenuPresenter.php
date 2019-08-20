<?php

namespace App\Presenters;

use Nwidart\Menus\Presenters\Presenter;

/**
 * Class SidebarMenuPresenter
 * @package App\Presenters
 */
class SidebarMenuPresenter extends Presenter
{
    /**
     * Get open tag wrapper.
     *
     * @return string
     */
    public function getOpenTagWrapper()
    {
        return '<nav id="sidebar" class="sidebar sidebar-offcanvas"><ul class="nav">';
    }

    /**
     * Get close tag wrapper.
     *
     * @return string
     */
    public function getCloseTagWrapper()
    {
        return '</ul></nav>';
    }

    /**
     * Get menu tag without dropdown wrapper.
     *
     * @param \Nwidart\Menus\MenuItem $item
     *
     * @return string
     */
    public function getMenuWithoutDropdownWrapper($item)
    {

        return '<li class="nav-item">
			<a class="nav-link ' . $this->getActiveState($item) . '" href="' . $item->getUrl() . '" ' . $item->getAttributes() . '">'
        . $item->getIcon() . ' ' . $item->title . '</a></li>' . PHP_EOL;
    }

    /**
     * {@inheritdoc }.
     */
    public function getActiveState($item, $state = ' active')
    {
        return $item->isActive() ? $state : null;
    }

    /**
     * Get active state on child items.
     *
     * @param $item
     * @param string $state
     *
     * @return null|string
     */
    public function getActiveStateOnChild($item, $state = ' active')
    {
        return $item->hasActiveOnChild() ? $state : null;
    }

    /**
     * Get active state on child items.
     *
     * @param $item
     * @param string $state
     *
     * @return null|string
     */
    public function getActiveStateOnChildArrow($item, $state = 'true')
    {
        return $item->hasActiveOnChild() ? $state : 'false';
    }

    /**
     * Get active state on child items.
     *
     * @param $item
     * @param string $state
     *
     * @return null|string
     */
    public function getActiveStateOnChildSubMenu($item, $state = 'show')
    {
        return $item->hasActiveOnChild() ? $state : '';
    }

    /**
     * {@inheritdoc }.
     */
    public function getDividerWrapper()
    {
        return '<li class="divider"></li>';
    }

    /**
     * {@inheritdoc }.
     */
    public function getHeaderWrapper($item)
    {
        return '<h3>' . strtoupper($item->title) . '</h3>';
    }

    /**
     * {@inheritdoc }.
     */
    public function getMenuWithDropDownWrapper($item)
    {
        return '
        <li class="nav-item">
            <a class="nav-link ' . $this->getActiveStateOnChild($item) . '" data-toggle="collapse" href="#'. $item->attributes['name'] . '" aria-expanded="' . $this->getActiveStateOnChildArrow($item) . '" aria-controls="'. $item->attributes['name'] . '">
                <i class="menu-icon  ' . $item->icon . '"></i>
                <span class="menu-title">'. $item->title .'</span>
                <i class="menu-arrow fas fa-chevron-right fa-fw"></i>
            </a>
            <div class="collapse ' . $this->getActiveStateOnChildSubMenu($item) . '" id="'. $item->attributes['name'] . '">
                <ul class="nav flex-column sub-menu">'
                . $this->getChildMenuItems($item) .
                '</ul>
            </div>
        </li>
        ' . PHP_EOL;
    }

    /**
     * Get multilevel menu wrapper.
     *
     * @param \Nwidart\Menus\MenuItem $item
     *
     * @return string`
     */
    public function getMultiLevelDropdownWrapper($item)
    {
        return $this->getMenuWithDropDownWrapper($item);
    }
}
