#!/bin/bash

OLDVERSION=$( cat VERSION )

git add .
git status
read -p "New version (last version was ${OLDVERSION}): "
NEWVERSION="${REPLY}"

read -p "Changelog comment (item 1): "
CHANGELOG1="${REPLY}"
read -p "Changelog comment (item 2): "
CHANGELOG2="${REPLY}"
read -p "Changelog comment (item 3): "
CHANGELOG3="${REPLY}"
read -p "Changelog comment (item 4): "
CHANGELOG4="${REPLY}"
read -p "Changelog comment (item 5): "
CHANGELOG5="${REPLY}"


