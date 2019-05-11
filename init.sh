#!/bin/bash
echo "Init Projects Directory"
chown www-data editor/projects -Rf
chown www-data editor/boardcast.json
chmod 0775 editor/projects -Rf
echo "Init Finished"


