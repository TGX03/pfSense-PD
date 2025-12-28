<?php
require_once("Net/IPv6.php");

$prefix_length = 56;

$subnets['opt1']['id'] = 0x20;
$subnets['opt1']['prefixlen'] = '60';
$subnets['opt1']['delegated_length'] = '64';

$subnets['opt3']['id'] = 0x40;
$subnets['opt3']['prefixlen'] = '60';
$subnets['opt3']['delegated_length'] = '64';

$subnets['opt5']['id'] = 0xa0;
$subnets['opt5']['prefixlen'] = '60';
$subnets['opt5']['delegated_length'] = '64';

$subnets['opt11']['id'] = 0xc;
$subnets['opt11']['prefixlen'] = '64';
$subnets['opt11']['delegated_length'] = '64';

config_read_file();

foreach($subnets as $interface => $prefix) {
    $realif = get_real_interface($interface);
    $ipv6 = find_interface_ipv6($realif);
    $main_prefix = Net_IPv6::getNetmask($ipv6, $prefix_length);
    $main_prefix = convert_ipv6_to_128bit($main_prefix);
    $prefix_id = decbin($prefix['id']);
    $main_prefix = substr($main_prefix, 0, 64 - strlen($prefix_id));

    $delegated_prefix = $main_prefix;
    $delegated_prefix .= $prefix_id;
    $delegated_prefix = str_pad($delegated_prefix, 128, "0", STR_PAD_RIGHT);
    $delegated_prefix = convert_128bit_to_ipv6($delegated_prefix);

    config_set_path('dhcpdv6/' . $interface . '/pdprefix', $delegated_prefix);
    config_set_path('dhcpdv6/' . $interface . '/pdprefixlen', $prefix['prefixlen']);
    config_set_path('dhcpdv6/' . $interface . '/pddellen', $prefix['delegated_length']);
}

write_config(desc: 'Adjust DHCPv6-PD', backup: false);
! killall kea-dhcp6
services_kea6_configure();

?>