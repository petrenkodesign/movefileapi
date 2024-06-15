# Move files API # v.1.0.2

## Requirements

PHP 7.4.x\
Composer 2.x\

## Installation

Clone project:

``git clone https://github.com/petrenkodesign/movefileapi.git``

go to project directory:

``cd  movefileapi``

resolves the dependencies, and installs them:

``composer update``

### API method __"movefiles"__
Moves files from remote server to local by one method from env configuration
[authorization key parameter -> api_token, method -> GET]:

``https://{server}/api/movefiles``

Laravel command:

``php artisan move:files``

#### App .env dependencies:
- API_TOKEN - static token for API requests
- SERVER_SRC_IP - IP of the server from which files should be copied
- SERVER_REM_IP - IP of the server to which the files are copied
- SERVER_SRC_DIR - directory from which the files are copied
- SERVER_REM_DIR - directory to which the files are copied
- FILES_MASK - mask of files to be moved
- FILE_MAX_SIZE - max size of moved files
- SERVER_SRC_USER - source server user
- SERVER_REM_USER - destination server user
- SSH_PORT - ssh port, one for all servers
- SERVER_RSA - path to the RSA key file
- SERVER_SRC_RSA_STRING - source server RSA key string
- SERVER_REM_RSA_STRING - destination server RSA key string
- IGNORE_EXISTING_FILES - parameter which set ignoring copying a files witch existing on destination (bool)

do not forget generate ssh key on the origin server:

``ssh-keygen``

сopying the public key to destination server using SSH:

``cat ~/.ssh/id_rsa.pub | ssh username@remote_host "mkdir -p ~/.ssh && touch ~/.ssh/authorized_keys && chmod -R go= ~/.ssh && cat >> ~/.ssh/authorized_keys"``

create or copy existing .env file to project root:\

### API method __"multimove"__

Mass moves files from one server to another according to the set configuration scenario
[authorization key parameter -> api_token, method -> GET]:

``https://{server}/api/multimove``

Laravel command:

``php artisan move:multifiles``

#### copying algorithm is written in an array in the configuration file:

``app/config/movepath.php``

array template:
['type', 'src_IP', 'src_dir', 'files_mask', 'rem_IP', 'rem_dir', 'src_user', 'rem_user', 'src_port', 'rem_port', 'src_rsa', 'rem_rsa', 'iex', 'src_rsa_file', 'rem_rsa_file']

- [0] 'type' - action, move or copy has two status cp - for copy, mv - for transfer
- [1] 'src_IP' - IP address of source server, for a local also use IP (ex: 192.2.2.1)
- [2] 'src_dir' - directory from which files should be copied (ex: '/input/OUTPUT/')
- [3] 'files_mask' - files mask, for exemple - '*.xml'
- [4] 'rem_IP' - destination server IP address (ex: 192.2.2.2)
- [5] 'rem_dir' - destination server directory (ex: '/input/INCOME/')
- [6] 'src_user' - (optional) - user of source server, string
- [7] 'rem_user' - (optional) - user of remote server, string
- [8] 'src_port' - (optional) - ssh port of source server, int
- [9] 'rem_port' - (optional) - ssh port of remote server, int
- [10] 'src_rsa' - (optional) - rsa key of source server, string
- [11] 'rem_rsa' - (optional) - rsa key of remote server, string
- [12] 'iex' - (optional) - ignore existing files copy on remote server, boolean
- [13] 'src_rsa_file' - (optional) - rsa key of source server, file
- [14] 'rem_rsa_file' - (optional) - rsa key of remote server, file

## Contributing

