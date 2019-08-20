<?php

namespace App\Presenters;

use Nwidart\Menus\Presenters\Presenter;
use Nwidart\Menus\MenuItem;

/**
 * Class NavBarMenuPresenter
 * @package App\Presenters
 */
class NavBarMenuPresenter extends Presenter
{
    /**
     * Get child menu items.
     *
     * @param \Nwidart\Menus\MenuItem $item
     *
     * @return string
     */
    public function getChildMenuItems(MenuItem $item)
    {
        $results = '';
        $children = collect($item->getChilds())->sortBy('order')->all();

        foreach ($children as $child) {
            if ($child->hidden()) {
                continue;
            }

            if ($child->hasSubMenu()) {
                $results .= $this->getMultiLevelDropdownWrapper($child);
            } elseif ($child->isHeader()) {
                $results .= $this->getHeaderWrapper($child);
            } elseif ($child->isDivider()) {
                $results .= $this->getDividerWrapper();
            } else {
                $results .= $this->getMenuWithoutDropdownWrapper($child);
            }
        }

        return( $results );
    }
    
    /**
     * Get open tag wrapper.
     *
     * @return string
     */
    public function getOpenTagWrapper()
    {
        return '<ul class="navbar-nav ml-auto">';
    }

    /**
     * Get close tag wrapper.
     *
     * @return string
     */
    public function getCloseTagWrapper()
    {
        return '</ul>';
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
        $prefix = (isset($item->attributes['just_content']) && $item->attributes['just_content'] == true) ? '' : '<a class="dropdown-item" href="' . $item->getUrl() . '" ' . $item->getAttributes() . '>';
        $suffix = (isset($item->attributes['just_content']) && $item->attributes['just_content'] == true) ? '' : '</a>';
        return $prefix . $this->getContent($item) . $suffix . PHP_EOL;
    }

    /**
     * {@inheritdoc }.
     */
    public function getActiveState($item, $state = ' class="current-page"')
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
    public function getActiveStateOnChild($item, $state = ' class="active-sm"')
    {
        return $item->hasActiveOnChild() ? $state : null;
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
		<li class="nav-item dropdown d-xl-inline-block user-dropdown">
			<a href="javascript:;" class="dropdown-toggle '.$this->getClasses($item).'" data-toggle="dropdown" aria-expanded="false">
				' . $item->getIcon() . ' ' . $this->getContent($item) .
			'</a>
            <div class="dropdown-menu '.$this->getDropdownClasses($item).'">
                ' . $this->getChildMenuItems($item) . '
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

    /**
     * Get icon.
     *
     * @param null|string $default
     *
     * @return string
     */
    public function getContent($item, $default = null)
    {
        return !empty($item->attributes['content']) ? $item->attributes['content'] : $default;
    }

    /**
     * @param $item
     * @param null $default
     * @return null
     */
    public function getClasses($item, $default = null)
    {
        return !empty($item->attributes['class']) ? $item->attributes['class'] : $default;
    }

    /**
     * @param $item
     * @param null $default
     * @return null
     */
    public function getDropdownClasses($item, $default = null)
    {
        return !empty($item->attributes['dropdown-class']) ? $item->attributes['dropdown-class'] : $default;
    }

    /**
     * @param $item
     * @param string $pos
     * @param bool $second
     * @return null|string
     */
    public function setIconPos($item, $pos = 'left', $second = false)
    {
        if(!empty($item->attributes['icon-pos']) && $item->attributes['icon-pos'] == $pos) {
            return ($item->attributes['icon-pos'] == 'left') ? $item->getIcon() . ' ' : ' ' . $item->getIcon();
        }elseif(empty($item->attributes['icon-pos']) && $second != false){
            return $item->getIcon();
        }

        return null;
    }
}
