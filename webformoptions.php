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
    <h1>Convert List to webform Options</h1>
    <label for="files">List of Option Names</label>
    <textarea name="opts" id="files"><?php print !empty($_REQUEST['opts']) ? $_REQUEST['opts'] : ''; ?></textarea>
    <button>Submit</button>
</form>
<br>
<hr>
<br>
<div>
    <?php
    if(!empty($_REQUEST['opts'])) {
        $opts = explode("\n", $_REQUEST['opts']);
        $opts_used = array();
        foreach($opts as $opt) {
            if(!empty($opt) && !in_array($opt, $opts_used)) {
                $opt = trim($opt);
                $key = preg_replace('/[^a-z0-9\_]/i','_', strtolower($opt));
                $key = trim($key, '_');
                $opt_formatted = "$key|$opt";


                print $opt_formatted . '<br>';
                $opts_used[] = $opt;
            }
        }
    }
    ?>
</div>