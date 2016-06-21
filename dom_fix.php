<?php
$nids = db_query("
    SELECT n.nid
    FROM node n
    WHERE n.type = 'student_trainee'
    AND n.status = 1
")->fetchCol();

$vivos = array();
foreach(node_load_multiple($nids) as $node) {
    if(!empty($node->field_vivo_id)) {
        $vivo = $node->field_vivo_id[LANGUAGE_NONE][0]['value'];
        if (!empty($node->field_image)) {
            $file_array = $node->field_image[LANGUAGE_NONE][0];
            $url = file_create_url($file_array['uri']);
            $file_headers = @get_headers($url);
            if ($file_headers[0] != 'HTTP/1.1 200 OK') {
                $vivos[] = $vivo;
            }
        }
    }
}

//dsm($vivos);

foreach($vivos as $vivo) {
    _som_scholars_add_scholar($vivo, false, true);
}