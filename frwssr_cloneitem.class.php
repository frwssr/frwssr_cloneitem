<?php

/**
 * A field type for cloning an item in a multi item region
 *
 * @package default
 * @author Nils Mielke, FEUERWASSER
 */
class PerchFieldType_frwssr_cloneitem extends PerchAPI_FieldType
{

    /**
     * Form fields for the edit page
     *
     * @param array $details
     * @return string
     */
    public function render_inputs($details = array())
    {
        $ftPath = PERCH_LOGINPATH . '/addons/fieldtypes/frwssr_cloneitem/';
        $perch = Perch::fetch();
        $perch->add_javascript($ftPath . 'init.js?v=0.1.0');

        $id = $this->Tag->input_id();
        $buttontext = $this->Tag->buttontext() ? $this->Tag->buttontext() : 'Clone item';
        $renamefield = $this->Tag->renamefield() ? ' data-renamefield="' . $this->Tag->renamefield() . '"': '';
        $renamepostfix = $this->Tag->renamepostfix() ? ' data-renamepostfix="' . $this->Tag->renamepostfix() . '"' : '';

        $s = $this->Form->text($id, $buttontext, $class='frwssr_cloneitem__button button button-simple', $limit=false, $type='submit', $attributes='readonly data-path="' . $ftPath . '"' . $renamefield . $renamepostfix);

        return $s;
    }

}