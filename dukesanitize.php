<?php
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
    <h1>Sanitize input for use in dukefiles</h1>
    <label for="files">List of Files</label>
    <textarea name="files" id="files"><?php print !empty($_REQUEST['files']) ? $_REQUEST['files'] : ''; ?></textarea>
    <button>Submit</button>
</form>
<br>
<hr>
<br>
<div>
    <?php
    if(!empty($_REQUEST['files'])) {
        $files = explode("\n", $_REQUEST['files']);
        $filenames = array();
        $files_used = array();
        foreach($files as $k => $file) {
            if(!empty($file) && !in_array($file, $files_used)) {
                $file_raw = preg_split('/\s/', trim($file));
                if(!is_array($file_raw)) {
                    $file_raw = array($file_raw);
                }

                $file = array();
                foreach($file_raw as $part) {
                    if(!empty($part)) {
                        $file[] = trim($part);
                    }
                }
                
                $filename = array_shift($file);
                if(strpos($filename, 'modified:') !== false) {
                    $filename = array_shift($file);
                }
                if(!in_array($filename, $filenames)) {
                    print $filename . '<br>';
                }
                $filenames[$k] = $filename;
                $files_used[] = $file;
            }
        }
    }
    ?>
</div>