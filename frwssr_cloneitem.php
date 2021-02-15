<?php
    $id = $_REQUEST['id'];
    $itm = $_REQUEST['itm'];
    $renamefield = $_REQUEST['renamefield'] ? $_REQUEST['renamefield'] : false;
    $renamepostfix = $_REQUEST['renamepostfix'] ? $_REQUEST['renamepostfix'] : ' (Copy)';

    $auth_page = true;

    include(__DIR__ . '/../../../core/inc/pre_config.php');
    include(__DIR__ . '/../../../config/config.php');


    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth.php');
    
    // Check for logout
    if ($CurrentUser->logged_in() && isset($_GET['logout']) && is_numeric($_GET['logout'])) {
        $CurrentUser->logout();
    }

    // If the user's logged in, clone item and related indices. Then send them to edit the new item
    if ($CurrentUser->logged_in()) {

        $DB = PerchDB::fetch();

        // get the item row to clone (latest revision) from perch3_content_items
        $itemData = $DB->get_row('SELECT * FROM perch3_content_items WHERE itemID = ' . $itm . ' AND itemRev = ( SELECT max(itemRev) FROM perch3_content_items WHERE itemID = ' . $itm . ' )');
        $itemLatestRev = $itemData['itemRev'];

        // get highest itemID in table to calculate next-highest (itemID is not auto-incremental)
        $topItemID = $DB->get_row('SELECT itemID FROM perch3_content_items WHERE itemID = ( SELECT max(itemID) FROM perch3_content_items )');
        $newItemID = ++$topItemID['itemID'];
        // change field values as required 
        $itemData['itemRowID'] = NULL;
        $itemData['itemID'] = $newItemID;


        // get all index rows related to the itemID to clone from perch3_content_index
        $indexData = $DB->get_rows('SELECT * FROM perch3_content_index WHERE itemID = ' . $itm . ' AND itemRev = ' . $itemLatestRev);
        // change field values as required and insert into perch3_content_index
        foreach($indexData as $key => $value) {
            $indexData[$key]['indexID'] = NULL;
            $indexData[$key]['itemID'] = $newItemID;
            if($renamefield && ($value['indexKey'] == $renamefield || $value['indexKey'] == $renamefield . '_raw' || $value['indexKey'] == $renamefield . '_processed')) {
                $indexData[$key]['indexValue'] = $indexData[$key]['indexValue'] . $renamepostfix;
            }
            $DB->insert('perch3_content_index', $indexData[$key]);
        }


        // updates itemJSON in itemData if a field to rename was provided
        if($renamefield) {
            $renameinjson = json_decode($itemData['itemJSON'], true);
            foreach( $renameinjson[$renamefield] as $key => $value) {
                if($key == 'raw' || $key == 'processed') {
                    $renameinjson[$renamefield][$key] .=  $renamepostfix;
                }
            }
            $renameinjson['_title'] .=  $renamepostfix;
            $itemData['itemJSON'] = json_encode($renameinjson);
        }
        // insert item into perch3_content_items
        $DB->insert('perch3_content_items', $itemData);


        // send user to newly cloned item
        header("Location: ../../../core/apps/content/edit/?id=" . $id . "&itm=" . $newItemID);

    }