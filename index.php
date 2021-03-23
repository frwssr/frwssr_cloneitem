<?php
    $auth_page = true;

    $siteroot = __DIR__ . '/../../../..';
    include($siteroot . '/perch/core/inc/pre_config.php');
    include($siteroot . '/perch/config/config.php');


    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth.php');
    
    // Check for logout
    if ($CurrentUser->logged_in() && isset($_GET['logout']) && is_numeric($_GET['logout'])) {
        $CurrentUser->logout();
    }

    // If the user's logged in, clone item and related indices. Then send them to edit the new item
    if ($CurrentUser->logged_in()) {

        try {
            // Will need a form posting to this page with the page ID in a query string named: "id"
            if (!$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)) {
                 throw new \Exception('No valid page ID passed though POST vars');
            }
            if (!$itm = filter_input(INPUT_GET, 'itm', FILTER_VALIDATE_INT)) {
                throw new \Exception('No valid item ID passed though POST vars');
            }
            if (!$renamefield = filter_input(INPUT_GET, 'renamefield', FILTER_SANITIZE_STRING)) {
                 throw new \Exception('There’s a problem with your renamefield ID');
            }
            if (!$renamepostfix = filter_input(INPUT_GET, 'renamepostfix', FILTER_SANITIZE_STRING)) {
                 throw new \Exception('There’s a problem with your renamepostfix string');
            }

            $DB = PerchDB::fetch();

            $itemsFactory = new PerchContent_Items();
            /** @var PerchContent_Item $item */

            $row = $DB->get_row('SELECT * FROM perch3_content_items WHERE itemID = ' . $itm . ' AND itemRev = ( SELECT max(itemRev) FROM perch3_content_items WHERE itemID = ' . $itm . ' )');
            $item = $itemsFactory->return_flattened_instance($row);
            $newMaxItemID = $itemsFactory->get_next_id();

            $item['itemRowID'] = NULL;
            $item['itemID'] = $newMaxItemID;

            // get all index rows related to the itemID to clone from perch3_content_index
            $indexData = $DB->get_rows('SELECT * FROM perch3_content_index WHERE itemID = ' . $itm . ' AND itemRev = ' . $item['itemRev']);
            // change field values as required and insert into perch3_content_index
            foreach($indexData as $key => $value) {
                $indexData[$key]['indexID'] = NULL;
                $indexData[$key]['itemID'] = $newMaxItemID;
                if($renamefield && ($value['indexKey'] == $renamefield || $value['indexKey'] == $renamefield . '_raw' || $value['indexKey'] == $renamefield . '_processed')) {
                    $indexData[$key]['indexValue'] = $indexData[$key]['indexValue'] . $renamepostfix;
                }
                $DB->insert('perch3_content_index', $indexData[$key]);
            }


            // updates itemJSON in item if a field to rename was provided
            if($renamefield) {
                $renameinjson = json_decode($item['itemJSON'], true);
                foreach( $renameinjson[$renamefield] as $key => $value) {
                    if($key == 'raw' || $key == 'processed') {
                        $renameinjson[$renamefield][$key] .=  $renamepostfix;
                    }
                }
                $renameinjson['_title'] .=  $renamepostfix;
                $item['itemJSON'] = json_encode($renameinjson);
            }

            // insert item into perch3_content_items
            $newItem = $itemsFactory->create( $item );
            if( is_object($newItem) ) { // created successfully
                // send user to newly cloned item
                header("Location: ../../../core/apps/content/edit/?id=" . $id . "&itm=" . $newMaxItemID);
            }


        } catch (\Exception $e) {
            //Redirect to an error page, whatever you want if something doesn't work out.
            PerchUtil::redirect('/404');
        }

    }