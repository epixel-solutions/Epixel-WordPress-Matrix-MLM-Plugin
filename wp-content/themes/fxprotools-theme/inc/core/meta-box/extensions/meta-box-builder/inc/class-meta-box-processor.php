<?php

/**
 * Parse JSON to Meta Box array
 *
 * @package Meta Box
 * @subpackage Meta Box Builder
 * @author Tan Nguyen <tan@fitwp.com>
 */
class Meta_Box_Processor
{
    /**
     * Store the meta box to be parsed
     * @var array
     */
    private $meta_box = array();

    /**
     * Construct is also main method
     *
     * @param Json $meta_box Meta Box Json to prepare to parse
     */
    public function __construct($meta_box)
    {
        $this->meta_box = $meta_box;

        $this->parse();
    }

    /**
     * Get Meta Box to save after parsed
     *
     * @return Array This Meta Box
     */
    public function get_meta_box()
    {
        if (is_array($this->meta_box))
            return $this->meta_box;
    }

    /**
     * Convert JSON which stored from post_excerpt to array to store on post_content
     *
     * @param  string /json $json_object Json Object
     *
     * @return mixed array
     */
    private function parse()
    {
        // By default, when get json form raw post data. It will have backslashes.
        // so remember to add stripslahses before decode
        $this->meta_box = json_decode(stripslashes($this->meta_box), true);

        $this->normalize_field($this->meta_box)
            ->parse_attrs($this->meta_box)
            ->normalize_conditional_logic($this->meta_box);

        // Clean Show / Hide, Include / Exclude
        $cleans = array('showhide', 'includeexclude');

        unset($this->meta_box['show'], $this->meta_box['hide'], $this->meta_box['include'], $this->meta_box['exclude']);

        foreach ($cleans as $clean) {
            if (isset($this->meta_box[$clean])) {
                foreach ($this->meta_box[$clean] as $key => $val) {
                    if (empty($val))
                        unset($this->meta_box[$clean][$key]);
                }

                if (isset($this->meta_box[$clean]['type']) && $this->meta_box[$clean]['type'] != 'off')
                    $this->meta_box[$this->meta_box[$clean]['type']] = $this->meta_box[$clean];

                unset($this->meta_box[$this->meta_box[$clean]['type']]['type']);
                unset($this->meta_box[$clean]);
            }
        }

        if (!is_array($this->meta_box['fields']))
            return;

        $this->set_fields_tab();

        $this->move_tabs_to_meta_box();

        $this->meta_box = $this->normalize_loop($this->meta_box);

        // Allows user define multidimmesional array by dot(.) notation
        $this->meta_box = array_unflatten($this->meta_box);
    }

    private function normalize_loop($field)
    {
        $sub_fields = array();

        foreach ($field['fields'] as $index => $sub_field) {
            $this->normalize_field($sub_field)
                ->parse_attrs($sub_field)
                ->normalize_conditional_logic($sub_field);

            if ( ! empty($sub_field['fields'])) {
                $sub_field = $this->normalize_loop($sub_field);
            }

            // Remove tabs fields from Meta Box
            if (isset($sub_field['type']) && $sub_field['type'] === 'tab')
                continue;

            if (isset($sub_field['type']) && $sub_field['type'] === 'group' && empty($sub_field['fields']))
                continue;

            $sub_fields[] = $sub_field;
        }

        $field['fields'] = $sub_fields;

        return $field;
    }

    private function normalize_field(&$field)
    {
        if (!is_array($field))
            return;

        foreach ($field as $key => $value) :

            if (in_array($value, array('true', 'false')))
                $field[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);

            // Handle some key / value pairs
            if ( in_array( $key, array( 'options', 'js_options', 'query_args' ) ) && is_array($value)) :
                // Options aren't affected with taxonomies
                // if ( $field['type'] === 'taxonomy' || $field['type'] === 'taxonomy_advanced' )
                // 	continue;

                $tmp_array = array();
                $tmp_std = array();

                foreach ($value as $arr) :
                   // $skip = empty($arr['key']);

                    if (in_array($arr['value'], array('true', 'false')))
                        $arr['value'] = filter_var($arr['value'], FILTER_VALIDATE_BOOLEAN);

                    $tmp_array[$arr['key']] = $arr['value'];
                    if (isset($arr['selected']) && $arr['selected'])
                        $tmp_std[] = $arr['key'];

                    // Push default value to std on Text List
                    if (isset($arr['default']) && !empty($arr['default'])) {
                        if ($field['type'] === 'fieldset_text')
                            $tmp_std[$arr['value']] = $arr['default'];
                        else
                            $tmp_std[] = $arr['default'];
                    }
                endforeach;

//                if (!isset($skip) || !$skip)
                    $field[$key] = $tmp_array;

                if (!empty($tmp_std)) {
                    $field['std'] = $tmp_std;
                }

                // if ( count( $tmp_std ) > 0 )
                // 	$field['std'] = $tmp_std[0];

            endif;
            // Remember unset the empty value on the last.
            if (empty($value))
                unset($field[$key]);
        endforeach;

        unset($field['$$hashKey']);

        if (empty($field['datalist']['id']))
            unset($field['datalist']);

        if (!empty($field['id']))
            $field['id'] = str_snake($field['id']);

        return $this;
    }

    /**
     * Move tabs from field to Meta Box array
     */
    private function move_tabs_to_meta_box()
    {
        foreach ($this->meta_box['fields'] as $field ) {
            if (isset($field['type']) && $field['type'] === 'tab') {

                if (!isset($this->meta_box['tabs']))
                    $this->meta_box['tabs'] = array();

                $label = isset($field['label']) ? $field['label'] : '';
                $icon  = isset($field['icon']) ? $field['icon'] : '';

                $this->meta_box['tabs'][$field['id']] = compact('label', 'icon');
            }
        }
    }

    private function parse_attrs(&$field)
    {
        if (!isset($field['attrs']))
            return $this;

        foreach ($field['attrs'] as $attr) {
            if (in_array($attr['value'], array('true', 'false')))
                $attr['value'] = filter_var($attr['value'], FILTER_VALIDATE_BOOLEAN);

            // Try parse Json on value if its Json
            $json = json_decode(stripslashes($attr['value']), true);

            if (is_array($json))
                $attr['value'] = $json;

            $field[$attr['key']] = $attr['value'];
        }

        unset($field['attrs']);

        return $this;
    }

    /**
     * Set field to correct tab
     */
    private function set_fields_tab()
    {
        if ($this->meta_box['fields'][0]['type'] !== 'tab')
            return $this;

        $previous_tab = 0;

        foreach ($this->meta_box['fields'] as $index => $field) {
            if ($field['type'] === 'tab')
                $previous_tab = $index;
            else
                $this->meta_box['fields'][$index]['tab'] = $this->meta_box['fields'][$previous_tab]['id'];
        }
    }

    private function normalize_conditional_logic(&$field)
    {
        if (empty($field['logic']) || !isset($field['logic']))
            return $this;

        $logic = $field['logic'];

        $visibility = $logic['visibility'] === 'visible' ? 'visible' : 'hidden';
        $relation = $logic['relation'] === 'and' ? 'and' : 'or';

        foreach ($logic['when'] as $index => $condition) {
            if (empty($condition[0]))
                unset($logic['when'][$index]);

            if (!isset($condition[2]) || is_null($condition[2]))
                $condition[2] = '';

            if (strpos($condition[2], ',') != false)
                $logic['when'][$index][2] = array_map('trim', explode(',', $condition[2]));
        }

        $field[$visibility] = array(
            'when' => $logic['when'],
            'relation' => $relation
        );

        unset($field['logic']);

        return $this;
    }
}