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
        $version = str_replace('7.x-', '', $v);
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
            $commands[] = 'drush dl ' . "$module-7.x-$project_version -y";
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