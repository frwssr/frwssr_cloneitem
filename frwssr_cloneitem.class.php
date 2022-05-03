<?php

/**
 * A field type for cloning an item in a multi item region
 *
 * @package default
 * @author Nils Mielke, FEUERWASSER
 * @version v0.1.3 - 2022-05-03
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
        $perch->add_javascript($ftPath . 'init.js?v=0.1.3');


        $id = $this->Tag->input_id();
        $buttontext = $this->Tag->buttontext() ? $this->Tag->buttontext() : '✌️ Clone item ⚠️';
        $renamefield = $this->Tag->renamefield() ? ' data-renamefield="' . $this->Tag->renamefield() . '"': '';
        $renamepostfix = $this->Tag->renamepostfix() ? ' data-renamepostfix="' . $this->Tag->renamepostfix() . '"' : ' data-renamepostfix=" (Copy)"';
        $unsetfields = $this->Tag->unsetfields() ? ' data-unsetfields="' . $this->Tag->unsetfields() . '"': '';
        $buttonbg = $this->Tag->buttonbg() ? ' style="background: ' . $this->Tag->buttonbg() . '"' : ' style="background: slategray"';

        $s = $this->Form->text($id, $buttontext, $class='frwssr_cloneitem__button button button-simple', $limit=false, $type='submit', $attributes='readonly data-path="' . $ftPath . '"' . $renamefield . $renamepostfix . $unsetfields . $buttonbg);

        return $s;
    }

}