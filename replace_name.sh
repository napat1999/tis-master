#!/bin/bash
    sed -i "s/GITLAB_IMAGE/$RELEASE_IMAGE/g" $CI_PROJECT_DIR/yaml/deployment.yaml