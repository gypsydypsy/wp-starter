#!/bin/bash

################################################################################
#            Use this script to execute post-deployment actions.               #
################################################################################

echo 'deploy'
. ~/.profile
DIR="$( cd "$( dirname "$0" )" && pwd )"
cd $DIR/../htdocs/wp-content/themes/havas-starter-pack/front/
npm install
npm run build
