# Move files API # v.1.0.0

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

generate ssh on the origin server:

``ssh-keygen``

сopying the public key to destination server using SSH:

``cat ~/.ssh/id_rsa.pub | ssh username@remote_host "mkdir -p ~/.ssh && touch ~/.ssh/authorized_keys && chmod -R go= ~/.ssh && cat >> ~/.ssh/authorized_keys"``

create or copy existing .env file to project root:\

### .env dependencies:
- API_TOKEN - static token for API requests 
- SERVER_SRC_IP - IP of the server from which files should be copied 
- SERVER_POINT_IP - IP of the server to which the files are copied 
- SERVER_SRC_DIR - directory from which the files are copied 
- SERVER_POINT_DIR - directory to which the files are copied 
- FILES_MASK - files mask 
- SERVER_POINT_USER - destination server user 
- SERVER_SSHKEY - path to the key file 
 

use this endpoint to move files [authorization key parameter -> api_token, method -> GET]:

``https://{server}/api/movefiles``

or use postman collection from root directory of project

``mfApi.postman_collection.json``

## Contributing

