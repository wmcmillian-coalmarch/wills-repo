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
    <textarea name="files" id="files"><?php print $_REQUEST['files']; ?></textarea>
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
                $file = preg_split('/\s/', trim($file));
                if(!is_array($file)) {
                    $file = array($file);
                }
                foreach($file as $f) {
                    if(strpos($f, 'sites/') !== false) {
                        $filename = trim($f);
                        break;
                    }
                }
                if(!empty($filename)) {
                    print $filename . '<br>';
                    $filenames[$k] = $filename;
                    $files_used[] = $file;
                }
            }
        }
    }
    ?>
</div>