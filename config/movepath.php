<?php
// configuration file to set up bulk file transfer
// each line is a separate movement script, line are separated by commas
// always follow the following sequence, changes will cause a crash
// ['type', 'src_IP', 'src_dir', 'files_mask', 'rem_IP', 'rem_dir', 'src_user', 'rem_user', 'src_port', 'rem_port', 'src_rsa', 'rem_rsa', 'iex', 'src_rsa_file', 'rem_rsa_file']
// [0] 'type' - action, move or copy has two status cp - for copy, mv - for transfer
// [1] 'src_IP' - IP address of source server, for a local also use IP (ex: 192.2.2.1)
// [2] 'src_dir' - directory from which files should be copied (ex: '/input/OUTPUT/')
// [3] 'files_mask' - files mask, for exemple - '*.xml'
// [4] 'rem_IP' - destination server IP address (ex: 192.2.2.2)
// [5] 'rem_dir' - destination server directory (ex: '/input/INCOME/')
// [6] 'src_user' - (optional) - user of source server, string
// [7] 'rem_user' - (optional) - user of remote server, string
// [8] 'src_port' - (optional) - ssh port of source server, int
// [9] 'rem_port' - (optional) - ssh port of remote server, int
// [10] 'src_rsa' - (optional) - rsa key of source server, string
// [11] 'rem_rsa' - (optional) - rsa key of remote server, string
// [12] 'iex' - (optional) - ignore existing files copy on remote server, boolean
// [13] 'src_rsa_file' - (optional) - rsa key of source server, file
// [14] 'rem_rsa_file' - (optional) - rsa key of remote server, file

return [
    ["mv","192.168.1.2","/src/dir/","*.xml","192.168.1.2","/rem/dir/","user1","user2","22","22","","","true","~/.ssh/rsa_src","~/.ssh/rsa_rem"],
];