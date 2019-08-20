<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Form;
use Session;
use Illuminate\Support\MessageBag;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     * 
     * Extends the form facade to give extra macros
     *
     * @return void
     */
    public function boot()
    {
        Form::macro('searchfrm', function ($route, $class = 'col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search', $hidden = null) {
            $html = $this->open(['route' => $route, 'method' => 'GET', 'class' => $class]) .
                '<div class="input-group">' .
                $this->input('text', 's', null, ['class' => 'form-control', 'placeholder' => 'Search for...']);

            if ($hidden != null) {
                foreach ($hidden as $k => $v) {
                    $html .= $this->hidden($k, $v);
                }
            }

            $html .= '<span class="input-group-btn">'.
                $this->button('Go', ['type'=>'submit', 'class'=>'btn btn-default btn-go animateMe']) .
                '<a href="'. url()->current(). '"><button type="button" class="btn btn-danger btn-clear animateMe">Clear</button></a>' .
                '</span>'.
                '</div>'.
                $this->close();
            return $html;
        });

        Form::macro('deletebtn', function ($route, $id, $text, $options = ['method' => 'DELETE', 'class' => 'd-inline-block'], $btn_class = 'btn btn-danger btn-xs') {
            $options = array_merge(['route' => [$route . '.destroy', 'id' => $id]], $options);
            $html = $this->open($options) .
                $this->button($text, ['type' => 'submit', 'class' => $btn_class]) .
                $this->close();
            return $html;
        });

        Form::macro('restorebtn', function ($route, $id, $text, $options = ['method' => 'POST', 'class' => 'd-inline-block'], $btn_class = 'btn btn-success btn-xs') {
            $options = array_merge(['route' => [$route . '.restore', 'id' => $id]], $options);
            $html = $this->open($options) .
                $this->button($text, ['type' => 'submit', 'class' => $btn_class]) .
                $this->close();
            return $html;
        });

        Form::macro('revertoverridebtn', function ($route, $id, $text, $options = ['method' => 'POST', 'class' => 'd-inline-block'], $btn_class = 'btn btn-success btn-xs') {
            $options = array_merge(['route' => [$route . '.revertOverride', 'id' => $id]], $options);
            $html = $this->open($options) .
                $this->button($text, ['type' => 'submit', 'class' => $btn_class]) .
                $this->close();
            return $html;
        });

        Form::macro('permdeletebtn', function ($route, $id, $text, $options = ['method' => 'POST', 'class' => 'd-inline-block'], $btn_class = 'btn btn-danger btn-xs') {
            $options = array_merge(['route' => [$route . '.delete', 'id' => $id]], $options);
            $html = $this->open($options) .
                $this->button($text, ['type' => 'submit', 'class' => $btn_class]) .
                $this->close();
            return $html;
        });

        Form::macro('labels', function ($name, $value = null, $options = [], $escape_html = true) {
            $html = (!empty($options['tooltip'])) ? $this->tooltip($options['tooltip']) : '';
            $html .= $this->label($name, $value, $options, $escape_html);
            return $html;
        });

        Form::macro('tooltip', function ($text = null) {
            if ($text != null)
                return '<span class="tooltipToggle">
                    <i class="fas fa-info-circle animateMe" aria-hidden="true"></i>
                    <span class="tooltipCon animateMe">' .
                    $text .
                    '</span>
                </span>';
        });

        Form::macro('errors', function ($name) {
            $errors = (!empty(Session::get('errors'))) ? Session::get('errors') : new MessageBag;
            $html = '';
            if ($errors->has($name)) {
                $html .= '<ul class="parsley-errors-list filled" id="parsley-id-' . $name . '">';
                foreach ($errors->get($name) as $error => $msg) {
                    $html .= '<li class="parsley-' . $error . '">' . $msg . '</li>';
                }
                $html .= '</ul>';
            }
            return $html;
        });

        Form::macro('inputs', function ($type, $name, $value = null, $options = []) {
            $value = $this->getValueAttribute($name, $value);

            $err = '';
            $errors = (!empty(Session::get('errors'))) ? Session::get('errors') : new MessageBag;

            //  dump($name);
            $name2 = str_replace('[', '.', $name);
            $name2 = str_replace(']', '', $name2);
//            dd($options);
            if ($errors->has($name)) {
                $options['class'] = $options['class'] . ' parsley-error';
                $err = $this->errors($name);
            } elseif ($errors->has($name2)) {
                $options['class'] = $options['class'] . ' parsley-error';
                $err = $this->errors($name2);
            }

            if ($type == 'checkbox')
                return '<div class="pull-left" style="margin-right:10px;">' . $this->checkable('checkbox', $name, $value, 0, $options) . '</div><div class="pull-left">' . $err . '</div>';
            if ($type == 'radio')
                return $this->checkable('radio', $name, $value, 0, $options) . $err;

            $icon = '';
            if (!empty($options['icon_class'])) {
                $icon = '<div class="input-prepend input-group"><span class="add-on input-group-addon"><i class="' . $options['icon_class'] . '"></i></span>';
                $err = '</div>' . $err;
                unset($options['icon_class']);
            }

            return $icon . $this->input($type, $name, $value, $options) . $err;
        });

        Form::macro('selects', function ($name, $list, $selected = null, $seloptions = [], $optsoptions = []) {
            $selected = $this->getValueAttribute($name, $selected);

            $err_name = preg_replace('/[^A-Za-z0-9_]/', '', $name);
            $parent_class = 'select-container';
            $err = '';
            $errors = (!empty(Session::get('errors'))) ? Session::get('errors') : new MessageBag;
            if ($errors->has($err_name)) {
                $parent_class .= ' parsley-error';
                $err = $this->errors($err_name);
            }

            $seloptions['data-parsley-errors-container'] = '.' . $err_name . '_error';
            $seloptions['data-parsley-class-handler'] = '.select-container';
            return '<div class="' . $parent_class . '">' . $this->select($name, $list, $selected, $seloptions, $optsoptions) . '<div class="' . $err_name . '_error">' . $err . '</div></div>';
        });

        Form::macro('textareas', function ($name, $value = null, $options = []) {
            $value = $this->getValueAttribute($name, $value);

            $err = '';
            $errors = (!empty(Session::get('errors'))) ? Session::get('errors') : new MessageBag;
            if ($errors->has($name)) {
                $options['class'] = $options['class'] . ' parsley-error';
                $err = $this->errors($name);
            }

            return $this->textarea($name, $value, $options) . $err;
        });

        Form::macro('files', function ($name, $options = []) {
            $err = '';
            $errors = (!empty(Session::get('errors'))) ? Session::get('errors') : new MessageBag;
            if ($errors->has($name)) {
                $options['class'] = $options['class'] . ' parsley-error';
                $err = $this->errors($name);
            }

            return $this->file($name, $options) . $err;
        });

        Form::macro('datepicker', function ($name, $value = null, $options = [], $format = 'd/m/Y H:i') {
            $value = $this->getValueAttribute($name, $value);
            if (is_a($value, 'Carbon\Carbon'))
                $value = $value->format($format);
            if ($value === null)
                $value = Carbon::now()->format($format);

            $err_name = preg_replace('/[^A-Za-z0-9_]/', '', $name);
            $parent_class = 'datetime-container';
            $err = '';
            $errors = (!empty(Session::get('errors'))) ? Session::get('errors') : new MessageBag;
            if ($errors->has($err_name)) {
                $parent_class .= ' parsley-error';
                $err = $this->errors($err_name);
            }

            $options['data-parsley-errors-container'] = '.' . $err_name . '_error';
            $options['data-parsley-class-handler'] = '.datetime-container';

            $html = '<div class="' . $parent_class . '" style="min-width:200px">';
            $html .= '<div class="input-group">';
            $html .= '<div class="input-group-addon">';
            $html .= '<i class="fa fa-calendar fa-fw"></i>';
            $html .= '</div>';
            $html .= $this->input('text', $name, $value, $options);
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="' . $err_name . '_error">' . $err . '</div>';

            return $html;
        });

        Form::macro('timepicker', function ($name, $selected = null, $seloptions = [], $optsoptions = [], $time_step = 5) {
            $err_name = preg_replace('/[^A-Za-z0-9_]/', '', $name);
            $parent_class = 'select-container';
            $err = '';
            $errors = (!empty(Session::get('errors'))) ? Session::get('errors') : new MessageBag;
            if ($errors->has($err_name)) {
                $parent_class .= ' parsley-error';
                $err = $this->errors($err_name);
            }

            $start_time = new DateTime('2010-01-01 00:00');
            $end_time = new DateTime('2010-01-01 23:55');
            $time_array = array();

            while ($start_time <= $end_time) {
                $time_array[$start_time->format('H:i')] = $start_time->format('H:i');
                $start_time->add(new DateInterval('PT' . $time_step . 'M'));
            }

            $seloptions['data-parsley-errors-container'] = '.' . $err_name . '_error';
            $seloptions['data-parsley-class-handler'] = '.select-container';

            $html = '<div class="' . $parent_class . ' input-prepend input-group">
                        <span class="add-on input-group-addon"><i class="fas fa-clock"></i></span>';
            $html .= $this->select($name, $time_array, $time_array[$selected], $seloptions, $optsoptions);
            $html .= '</div><div class="' . $err_name . '_error">' . $err . '</div>';

            return $html;
        });

        Form::macro('radio_buttons', function ($name = '', $value = null, $default = 0, $options = array('buttons' => ['yes' => ['text' => 'Yes', 'value' => 1], 'no' => ['text' => 'No', 'value' => 0]])) {
            $value = $this->getValueAttribute($name, $value);
            $value = ($value == null) ? $default : $value;

            $err = '';
            $errors = (!empty(Session::get('errors'))) ? Session::get('errors') : new MessageBag;
            if ($errors->has($name))
                $err = $this->errors($name);

            $classes = (!empty($options['class'])) ? $options['class'] : '';
            $styles = (!empty($options['style'])) ? 'style="' . $options['style'] . '"' : '';
            $parent_wrapper = (!empty($options['parent_wrapper'])) ? $options['parent_wrapper'] : 'div';
            $child_wrapper = (!empty($options['child_wrapper'])) ? $options['child_wrapper'] : 'label';
            $html = '<' . $parent_wrapper . ' class="btn-group ' . $classes . '" data-toggle="buttons" ' . $styles . '>';
            foreach ($options['buttons'] as $button) {
                $checked = ($value == $button['value']) ? true : false;
                $html .= '<' . $child_wrapper . ' class="btn btn-default animateMe ' . (($checked) ? 'active' : '') . '">';
                $html .= $this->checkable('radio', $name, $button['value'], $checked, []);
                $html .= $button['text'];
                $html .= '</' . $child_wrapper . '>';
            }
            $html .= '</' . $parent_wrapper . '>';

            return $html . $err;
        });
    }
}
