<?php
require "app.php";
$redis = new Predis\Client();
function testValid($redis){
    print('test valid...');
    assert($redis->exists('subdomains'));
    assert($redis->exists('cookie:dlp-avast:chip'));
    assert($redis->exists('cookie:dlp-avast:filehippo'));
    assert($redis->exists('cookie:dlp-avg:comss'));
    assert($redis->exists('cookie:dlp-avg:ultradownloads'));
}

function testDomains($redis){
    print('test subdomains...');
    $value = $redis->get('subdomains');
    $domains = json_decode($value);   
    assert(in_array("http://www.avg.com", $domains));
    assert(in_array("http://files.avast.com", $domains));
    assert(in_array("http://cdn-download.avastbrowser.com", $domains));
    assert(in_array("https://s-install.avcdn.net", $domains));
}

function testCookies($redis){
    print('test cookies...');
    assert($redis->get("cookie:dlp-avast:chip") == "mmm_cip_dlp_777_ppc_m");
    assert($redis->get("cookie:dlp-avast:filehippo") == "mmm_fhp_dlp_777_ppc_m");
    assert($redis->get("cookie:dlp-avg:comss") == "mmm_cms_dlp_779_ppc_m");
    assert($redis->get("cookie:dlp-avg:ultradownloads") == "mmm_uld_dlp_779_ppc_m");
}

testValid($redis);
testDomains($redis);
testCookies($redis);
?>