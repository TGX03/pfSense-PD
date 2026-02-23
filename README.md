# pfSense-PD
This is a hacky way to get dynamic prefix delegation using Kea DHCPv6 on pfSense.
The script is written for my own configuration, if you wish to use it, simply adjust the content of $subnets to your needs, and adjust $prefix_length to whatever your provider assigns you.

This script assumes the interface connected to the router and the prefix you want to delegate to that router stem from the same parent prefix with length $prefix_length. If you want to delegate a prefix from another parent prefix, this won't work for you.
The script works for multiple parent prefixes, assuming the previos statement holds true.

If you have multiple WAN prefixes routed to you with different prefix lengths, simply have multiple instances of this script with the corresponding settings.

To run this script, copy the contents between "\<?php" and "?\>" and execute it using the pfSense PHP shell: https://docs.netgate.com/pfsense/en/latest/development/php-shell.html

I'm running the script by inserting it into interfaces.inc and rc.newwanipv6 in pfSense. My adjustments to those files are also included in the repo, however I probably missed some bits, but for now, it works.

This is a pretty hacky solution, but it works for me for now, and hopefully there will be a proper solution for this someday.
