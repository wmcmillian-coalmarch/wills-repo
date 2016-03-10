<?php
$HOME = '/Users/willmcmillian';
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
    $duke_sites[] = '<option value="'.$dir.'">'.$dir.'</option>';
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
    $sites[] = '<option value="'.$dir.'">'.$dir.'</option>';
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

    textarea {
        width: 100%;
        max-width: 600px;
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

            foreach($files as $k => $file) {
                if(!empty($file)) {
                    $filename = $prepend . $file;
                    print $filename . '<br>';
                    $filenames[$k] = $filename;
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
                print 'cp ' . $filename . ' ' . str_replace('sites/all/',$repo ,$files[$k]) . ';';
            }
        }
    ?>
</div>