<?php
if($_SERVER['DOCUMENT_ROOT'] == '/usr/local/var/www/htdocs') {
    $HOME = '/Users/willmcmillian';
}
else {
    $path = explode('/', $_SERVER['DOCUMENT_ROOT']);
    $HOME = "/{$path[1]}/{$path[2]}";
}
$duke_dir = $HOME . '/Duke';
$sites_dir = $HOME . '/Sites';

$dir_r = scandir($duke_dir);

$duke_sites = array();
foreach($dir_r as $dir) {
    $loc = $duke_dir . '/' . $dir;
    if ($dir === '.'
        || $dir === '..'
        || strpos('.', $dir) === 0
        || !is_dir($loc)
    ) {
        continue;
    }
    if(!empty($_GET['duke-site']) && $_GET['duke-site'] == $dir) {
        $selected = ' selected="selected"';
    }
    else {
        $selected = '';
    }
    $duke_sites[] = '<option value="'.$dir.'"'.$selected.'>'.$dir.'</option>';
}

$sites = array();

$dir_r = scandir($sites_dir);

foreach($dir_r as $dir) {
    $loc = $sites_dir . '/' . $dir;
    if ($dir === '.'
        || $dir === '..'
        || strpos('.', $dir) === 0
        || !is_dir($loc)
    ) {
        continue;
    }
    if(!empty($_GET['duke-site']) && $_GET['site'] == $dir) {
        $selected = ' selected="selected"';
    }
    else {
        $selected = '';
    }
    $sites[] = '<option value="'.$dir.'"'.$selected.'>'.$dir.'</option>';
}


?>
<style>
    input, textarea, label {
        display: block;
    }

    p {
        margin-bottom: 10px;
    }

    label {
        font-weight: bold;
    }

    form {
        max-width: 600px;
    }

    textarea {
        width: 100%;
        height: 300px;
    }

</style>
<form>
    <h1>Create Copy Command From List of Files</h1>
    <label for="files">List of Files</label>
    <textarea name="files" id="files"></textarea>
    <p>Seperate files with a new line</p>
    <label for="site">Site</label>
    <select name="site" id="site">
        <?php print implode("\n",$sites); ?>
    </select>
    <p>Optional site parameter. This will prepend the file names with '~/Sites/[sitename]'.</p>
    <label for="duke-site">Duke Site</label>
    <select name="duke-site" id="duke-site">
        <?php print implode("\n",$duke_sites); ?>
    </select>
    <p>Name of the duke repo to copy to.</p>
    <button>Submit</button>
</form>
<br>
<hr>
<br>
<div>
    <?php
        if(!empty($_REQUEST['files'])) {
            $files = explode("\n", $_REQUEST['files']);
            $prepend = (!empty($_REQUEST['site'])) ? $sites_dir . '/' . $_REQUEST['site'] . '/' : '';

            $filenames = array();
            $files_used = array();
            foreach($files as $k => $file) {
                if(!empty($file) && !in_array($file, $files_used)) {
                    $filename = $prepend . $file;
                    print $filename . '<br>';
                    $filenames[$k] = $filename;
                    $files_used[] = $file;
                }
            }
        }
    ?>
</div>
<br>
<hr>
<br>
<div>
    <?php
        $repo = (!empty($_REQUEST['duke-site'])) ? $duke_dir . '/' . $_REQUEST['duke-site'] . '/' : '';
        if(!empty($filenames)) {
            foreach($filenames as $k => $filename) {
                $filename = trim($filename);
                $dest = trim(str_replace('sites/all/',$repo ,$files[$k]));
                switch($_REQUEST['duke-site']) {
                    case 'site_dhvi':
                    case 'site_chavi-id':
                    case 'site_eqapol_dhvi_duke_edu':
                        if(strpos($dest, '/themes/' )) {
                           $dest =  str_replace('/themes/', '/themes/custom/', $dest);
                        }
                }
                
                
                if(is_dir($filename)) {
                    $dir = basename($dest);
                    $dest = substr($dest, 0, (0 - (strlen($dir) + 1)));
                    $cmd = "mkdir -p $dest; cp -R $filename $dest/;";
                }
                elseif(!is_file($dest)) {
                    $file = basename($dest);
                    $dest = substr($dest, 0, (0 - (strlen($file) + 1)));
                    $cmd = "mkdir -p $dest; cp $filename $dest/;";
                }
                else {
                    $cmd = "cp $filename $dest;";
                }


                print $cmd;
            }
        }
    ?>
</div>