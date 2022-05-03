<?php
    $auth_page = true;

    $perchroot = __DIR__ . '/../../..';
    include($perchroot . '/core/inc/pre_config.php');
    include($perchroot . '/config/config.php');


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
            if (isset($_GET['renamefield']) && !$renamefield = filter_input(INPUT_GET, 'renamefield', FILTER_SANITIZE_STRING)) {
                 throw new \Exception('There’s a problem with your renamefield ID');
            }
            if (!$renamepostfix = filter_input(INPUT_GET, 'renamepostfix', FILTER_SANITIZE_STRING)) {
                throw new \Exception('There’s a problem with your renamepostfix string');
            }
            if (isset($_GET['unsetfields']) && !$unsetfieldsInput = filter_input(INPUT_GET, 'unsetfields', FILTER_SANITIZE_STRING)) {
                 throw new \Exception('There’s a problem with your unsetfields');
            }
            if ($unsetfieldsInput) {
                $unsetfields = explode(',', $unsetfieldsInput);
            }

            $DB = PerchDB::fetch();

            $itemsFactory = new PerchContent_Items();
            /** @var PerchContent_Item $item */

            $row = $DB->get_row('SELECT * FROM ' . PERCH_DB_PREFIX . 'content_items WHERE itemID = ' . $itm . ' AND itemRev = ( SELECT max(itemRev) FROM ' . PERCH_DB_PREFIX . 'content_items WHERE itemID = ' . $itm . ' )');
            $item = $itemsFactory->return_flattened_instance($row);
            $newMaxItemID = $itemsFactory->get_next_id();

            $item['itemRowID'] = NULL;
            $item['itemID'] = $newMaxItemID;

            // get all index rows related to the itemID to clone from {PERCH_DB_PREFIX}content_index
            $indexData = $DB->get_rows('SELECT * FROM ' . PERCH_DB_PREFIX . 'content_index WHERE itemID = ' . $itm . ' AND itemRev = ' . $item['itemRev']);
            // change field values as required and insert into {PERCH_DB_PREFIX}content_index
            foreach($indexData as $key => $value) {
                $indexData[$key]['indexID'] = NULL;
                $indexData[$key]['itemID'] = $newMaxItemID;

                if($renamefield && ($value['indexKey'] == $renamefield || $value['indexKey'] == $renamefield . '_raw' || $value['indexKey'] == $renamefield . '_processed')) {
                    $indexData[$key]['indexValue'] = $indexData[$key]['indexValue'] . $renamepostfix;
                }
                $DB->insert('' . PERCH_DB_PREFIX . 'content_index', $indexData[$key]);
            }


            $itemJSON = json_decode($item['itemJSON'], true);
            // updates itemJSON in item if fields to unset were provided
            foreach( $unsetfields as $unsetfield) {
                $unset = explode('|', $unsetfield);
                $itemJSON[$unset[0]] = $unset[1] ? $unset[1] : '';
            }
            // updates itemJSON in item if a field to rename was provided
            if($renamefield) {
                if(is_array($itemJSON[$renamefield])) {
                    foreach( $itemJSON[$renamefield] as $key => $value) {
                        if($key == 'raw' || $key == 'processed') {
                            $itemJSON[$renamefield][$key] .=  $renamepostfix;
                        }
                    }
                } else {
                    $itemJSON[$renamefield] .=  $renamepostfix;
                }
                $itemJSON['_title'] .= $renamepostfix;
            }

            $item['itemJSON'] = json_encode($itemJSON);

            // insert item into {PERCH_DB_PREFIX}content_items
            $newItem = $itemsFactory->create( $item );
            if( is_object($newItem) ) { // created successfully
                // send user to newly cloned item
                header("Location: ../../../core/apps/content/edit/?id=" . $id . "&itm=" . $newMaxItemID);
            }


        } catch (\Exception $e) {
            //Redirect to an error page, whatever you want if something doesn't work out.
            print '<div style="background-color: tomato; border-bottom: 20px solid teal; border-radius: 5px; color: white; display: inline-block; font-family: sans-serif; margin: 1em; letter-spacing: .025em; line-height: 1.4; max-width: 60ch; padding: .75em 1em;">⚠️';
            print '<p style="font-weight: bold;">';
            print $e;
            print '</p>';
            print '<p style="font-size: .8em;">Sorry for the fail.<br>Please contact your web developer or—if you are said web developer—raise an issue in the <a href="https://github.com/frwssr/frwssr_cloneitem/issues" target="_blank" rel="noopener noreferrer" style="color: teal;">GitHub repo</a>.<br>Thank you. 🙏</p>';
            print '</div>';
        }

    }