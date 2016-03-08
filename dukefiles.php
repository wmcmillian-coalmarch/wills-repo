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
    <input type="text" name="site" id="site">
    <p>Optional site parameter. This will prepend the file names with '~/Sites/[sitename]'.</p>
    <button>Submit</button>
</form>
<br>
<hr>
<br>
<div>
    <?php
        if(!empty($_REQUEST['files'])) {
            $files = explode("\n", $_REQUEST['files']);
            $prepend = (!empty($_REQUEST['site'])) ? '~/Sites/' . $_REQUEST['site'] . '/' : '';

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
        if(!empty($filenames)) {
            foreach($filenames as $k => $filename) {
                print 'cp ' . $filename . ' ' . str_replace('sites/all', '.',$files[$k]) . ';';
            }
        }
    ?>
</div>