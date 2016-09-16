<?php

require_once 'Spyc.php';

function duke_drush_command(){
    $items = array();

    $items['duke-check-modules'] = array(
        'description' => 'Check site\'s modules against a yaml file provided by Duke.',
        'aliases' => array('dcm'),
        'options' => array(
            'file' => 'yaml file to check against. Defaults to ~/Duke/platform_d7_dhts_som/make.yaml'
        ),
    );

    $items['duke-prepare-site'] = array(
        'description' => 'Remove pantheon modules from the database and convert coalmarch users to netIDs',
        'aliases' => array('dps'),
        'options' => array(
            'file' => 'a php file (do NOT close the php tag) containing an array of additional users and netIds saved tot he variable $users eg: 
                        <?php 
                        $users = array(
                            \'username\' => \'netID@duke.edu\'
                        )'
        ),
    );

    $items['duke-prepare-site-reverse'] = array(
        'description' => 'Reverse of duke-prepare-site so that a duke site can be made into a pantheon one',
        'aliases' => array('dpsr'),
    );

    return $items;
}

function drush_duke_check_modules(){
    $file = drush_get_option('file');
    if(empty($file)) {
        $home = getenv('HOME');
        $file = $home . '/Duke/platform_d7_dhts_som/make.yaml';
    }
    $yaml = file_get_contents($file);

    if(empty($yaml)) {
        drush_set_error('No yaml content available!');
        exit(1);
    }

    $platform = Spyc::YAMLLoadString($yaml);
    $projects = $platform['projects'];
    $core = $platform['core'];

    $current_modules = db_query("
        SELECT *
        FROM system
        WHERE status = 1
        AND type = 'module'
        AND filename LIKE 'sites/%.module'
    ")->fetchAll(PDO::FETCH_ASSOC);

    $modules = array();
    foreach($current_modules as $k => $m) {
        $file = $m['filename'];
        if(!preg_match('/sites\/[^\/]+\/modules\/[^\/]+\/[^\/]+.module/', $file) && !preg_match('/sites\/[^\/]+\/modules\/contrib\/[^\/]+\/[^\/]+.module/', $file)){
            continue;
        }

        $info = unserialize($m['info']);

        $modules[$m['name']] = $info['version'];
    }

    $commands = array();
    $not_found = array();
    $no_match = array();
    foreach($modules as $module => $v) {
        $version = str_replace($core.'-', '', $v);
        $project_version = $projects[$module];
        if(is_array($project_version)) {
            $project_version = $project_version['version'];
        }
        if(empty($projects[$module])) {
            $not_found[] = "$module not found on platform\n";
            continue;
        }

        if($project_version != $version) {
            $no_match[] = "$module version doesn't match: Site = $version. Platform = $project_version\n";
            $commands[] = 'drush dl ' . "$module-$core-$project_version -y";
        }
    }

    if(!empty($not_found)) {
        print "\n";
        print "NOT FOUND! The following modules weren't found in the pltform:\n\n";
        foreach($not_found as $m) {
            print "    $m";
        }
        print "\n";
    }
    if(!empty($no_match)) {
        print "\n";
        print "NO MATCH! The following modules were found on platform but the versions didn't match:\n\n";
        foreach($no_match as $m) {
            print "    $m";
        }
        print "\n";
    }

    if(!empty($commands)) {
        print "\n";
        if(drush_confirm('Download found modules?')){
           drush_shell_exec(implode(" && ", $commands));
        }
        print "\n";
    }

}

function drush_duke_prepare_site(){
    $file = drush_get_option('file');
    $users = array();
    if(!empty($file)) {
        require_once $file;
    }

    $users = array_merge($users, array(
        'dwhite' => 'dmw60@duke.edu',
        'lroger' => 'lhr8@duke.edu',
        'cnein' => 'cbn5@duke.edu',
        'rkirkpatrick' => 'rdk11@duke.edu',
        'wmcmillian' => 'wdm10@duke.edu',
        'lhenderson' => 'lah82@duke.edu',
        'jfekete' => 'jrf26@duke.edu',
        'awelles' => 'amw84@duke.edu'
    ));

    $d_users = entity_load('user');

    //get rid of users 0 and 1
    array_shift($d_users);
    array_shift($d_users);

    $not_found = array();
    foreach ($d_users as $user) {
        if(!in_array($user->name,array_keys($users))) {
            $not_found[$user->uid] = $user->name;
            continue;
        }

        if (shib_provision_is_provisioned($user->uid)) {
            shib_provision_update_provision($user->uid, $user->name, $user->mail);
        }
        else {
            shib_provision_provision($user->uid, $user->name, $user->mail);
        }

        user_save($user, array('name' => $users[$user->name]));
    }

    if(!empty($not_found)) {
        global $base_url;
        $message = 'The following users were not updated. These should be converted to netIDs manually: ' . "\n";
        $i = 1;
        foreach($not_found as $uid => $nf) {
            $url = $base_url . '/user/' . $uid . '/edit';
            $message .= "$nf => $url";
            if($i != count($not_found)) {
                $message .= "\n";
            }
            ++$i;
        }


        drush_log($message, 'warning');
    }

    $pantheon_modules = array(
        'pantheon_api',
        'pantheon_login',
        'pantheon_apachesolr'
    );

    drush_module_disable($pantheon_modules);
    drush_module_uninstall($pantheon_modules);
}

function drush_duke_prepare_site_reverse() {
    $pantheon_modules = array(
        'pantheon_api',
        'pantheon_login'
    );

    drush_module_enable($pantheon_modules);

    $users = array(
        'dwhite' => 'dmw60@duke.edu',
        'lroger' => 'lhr8@duke.edu',
        'cnein' => 'cbn5@duke.edu',
        'rkirkpatrick' => 'rdk11@duke.edu',
        'wmcmillian' => 'wdm10@duke.edu',
        'lhenderson' => 'lah82@duke.edu',
        'jfekete' => 'jrf26@duke.edu',
        'awelles' => 'amw84@duke.edu'
    );
    $r_users = array();
    foreach($users as $pu => $du) {
        $r_users[$du] = $pu;
    }

    $d_users = entity_load('user');

    //get rid of users 0 and 1
    array_shift($d_users);
    array_shift($d_users);

    foreach ($d_users as $user) {
        if(!in_array($user->name,array_keys($r_users))) {
            $not_found[$user->uid] = $user->name;
            continue;
        }

        if (shib_provision_is_provisioned($user->uid)) {
            shib_provision_update_provision($user->uid, $user->name, $user->mail);
        }
        else {
            shib_provision_provision($user->uid, $user->name, $user->mail);
        }

        user_save($user, array('name' => $r_users[$user->name]));
    }
}